import { useState } from 'react';
import { api } from '@/lib/api';
import type { components } from '@/lib/api-types';

type PostFormProps = {
  threadId: number;
  onCreated?: (post: components['schemas']['Post'] & { reactions?: Record<string, number>; myReaction?: string | null }) => void;
};

export default function PostForm({ threadId, onCreated }: PostFormProps) {
  const [body, setBody] = useState('');
  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    const { data, error } = await api.POST('/threads/{id}/posts', {
      params: {
        path: {
          id: threadId,
        },
      },
      body: {
        body,
      },
    });
    if (error) {
      console.error('Failed to create post:', error);
      return;
    }
    if (!data) {
      console.error('No data returned');
      return;
    }
    setBody('');
    onCreated?.({ ...data.data, reactions: {}, myReaction: null });
  };

  return (
    <form onSubmit={submit}>
      <textarea
        value={body}
        onChange={(e) => setBody(e.target.value)}
        placeholder="Write a post..."
      />
      <button type="submit">Post</button>
    </form>
  );
}
