'use client';
import { useEffect, useState } from 'react';
import { api } from '../lib/api';

type Thread = { id: number; title: string };

export default function Home() {
  const [threads, setThreads] = useState<Thread[]>([]);
  useEffect(() => {
    api('/threads').then((r) => setThreads(r.data)).catch(() => {});
  }, []);
  return (
    <div>
      <a href="/login">Login</a>
      <ul>
        {threads.map((t) => (
          <li key={t.id}>{t.title}</li>
        ))}
      </ul>
    </div>
  );
}
