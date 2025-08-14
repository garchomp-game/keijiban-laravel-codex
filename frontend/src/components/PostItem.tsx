import { useState } from 'react';
import { api } from '@/lib/api';
import type { components } from '@/lib/api-types';

export type ReactionCounts = Record<string, number>;

export type Post = components['schemas']['Post'] & {
  reactions: ReactionCounts;
  myReaction?: string | null;
};

type PostItemProps = {
  post: Post;
};

export default function PostItem({ post }: PostItemProps) {
  const [reactions, setReactions] = useState<ReactionCounts>(post.reactions);
  const [myReaction, setMyReaction] = useState<string | null>(post.myReaction || null);

  const react = async (type: string) => {
    if (myReaction === type) {
      // Delete reaction
      const { error } = await api.DELETE('/posts/{id}/reactions', {
        params: {
          path: {
            id: post.id,
          },
        },
      });
      if (error) {
        console.error('Failed to delete reaction:', error);
        return;
      }
    } else {
      // Create or update reaction
      const { error } = await api.POST('/posts/{id}/reactions', {
        params: {
          path: {
            id: post.id,
          },
        },
        body: {
          type,
        },
      });
      if (error) {
        console.error('Failed to create reaction:', error);
        return;
      }
    }

    setReactions((prev) => {
      const next = { ...prev };
      if (myReaction === type) {
        next[type] = (next[type] || 1) - 1;
        setMyReaction(null);
      } else {
        if (myReaction) {
          next[myReaction] = (next[myReaction] || 1) - 1;
        }
        next[type] = (next[type] || 0) + 1;
        setMyReaction(type);
      }
      return next;
    });
  };

  return (
    <div>
      <p>{post.body}</p>
      <div>
        <button onClick={() => react('like')}>
          👍 {reactions['like'] || 0}
        </button>
        <button onClick={() => react('dislike')}>
          👎 {reactions['dislike'] || 0}
        </button>
      </div>
    </div>
  );
}
