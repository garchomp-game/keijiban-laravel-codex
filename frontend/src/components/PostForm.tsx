import { useState } from 'react';
import { api } from '@/lib/api';
import type { Post } from './PostItem';

type PostFormProps = {
  threadId: number;
  onCreated?: (post: Post) => void;
};

export default function PostForm({ threadId, onCreated }: PostFormProps) {
  const [body, setBody] = useState('');
  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    const res = await api(`/threads/${threadId}/posts`, {
      method: 'POST',
      body: JSON.stringify({ body }),
    });
    setBody('');
    onCreated?.(res.data);
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
