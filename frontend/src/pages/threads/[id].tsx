import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import { api } from '@/lib/api';

interface User {
  id: number;
  name: string;
}

interface Thread {
  id: number;
  title: string;
}

interface Post {
  id: number;
  body: string;
  user: User;
}

export default function ThreadPage() {
  const router = useRouter();
  const { id } = router.query;

  const [thread, setThread] = useState<Thread | null>(null);
  const [posts, setPosts] = useState<Post[]>([]);
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const threadId = Array.isArray(id) ? id[0] : id;
    if (!threadId) return;

    setLoading(true);
    Promise.all([
      api(`/threads/${threadId}`),
      api(`/threads/${threadId}/posts`),
      api('/user').catch(() => null),
    ])
      .then(([threadRes, postsRes, userRes]) => {
        setThread(threadRes.data);
        setPosts(postsRes.data);
        if (userRes?.data) setCurrentUser(userRes.data);
        setLoading(false);
      })
      .catch(() => {
        setError('Failed to load thread');
        setLoading(false);
      });
  }, [id]);

  const editPost = async (post: Post) => {
    const body = prompt('Edit post', post.body);
    if (!body) return;
    try {
      await api(`/threads/${thread?.id}/posts/${post.id}`, {
        method: 'PATCH',
        body: JSON.stringify({ body }),
      });
      setPosts((prev) => prev.map((p) => (p.id === post.id ? { ...p, body } : p)));
    } catch {
      alert('Failed to edit post');
    }
  };

  const deletePost = async (post: Post) => {
    if (!confirm('Delete this post?')) return;
    try {
      await api(`/threads/${thread?.id}/posts/${post.id}`, { method: 'DELETE' });
      setPosts((prev) => prev.filter((p) => p.id !== post.id));
    } catch {
      alert('Failed to delete post');
    }
  };

  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;
  if (!thread) return <p>Thread not found</p>;

  return (
    <div>
      <h1>{thread.title}</h1>
      <ul>
        {posts.map((post) => (
          <li key={post.id}>
            <p>{post.body}</p>
            <small>by {post.user.name}</small>
            {currentUser && currentUser.id === post.user.id && (
              <span>
                <button onClick={() => editPost(post)}>Edit</button>
                <button onClick={() => deletePost(post)}>Delete</button>
              </span>
            )}
          </li>
        ))}
      </ul>
    </div>
  );
}

