'use client';
import { useEffect, useState } from 'react';
import { api } from '../lib/api';
import type { components } from '../lib/api-types';

type Thread = components['schemas']['Thread'];

export default function Home() {
  const [threads, setThreads] = useState<Thread[]>([]);
  useEffect(() => {
    api.GET('/threads').then((r) => {
      if (r.data) setThreads(r.data.data);
    });
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
