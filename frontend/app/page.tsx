"use client";
import { useEffect, useState } from "react";
import Link from "next/link";
import { api } from "../lib/api";
import type { components } from "../lib/api-types";

type Thread = components['schemas']['Thread'];

export default function Home() {
  const [threads, setThreads] = useState<Thread[]>([]);

  useEffect(() => {
    api.GET("/threads").then((r) => {
      if (r.data) setThreads(r.data.data);
    });
  }, []);

  return (
    <main className="p-6">
      <div className="mb-4 text-right">
        <Link href="/login" className="text-blue-600 hover:underline">
          Login
        </Link>
      </div>
      <ul className="space-y-2">
        {threads.map((t) => (
          <li key={t.id} className="rounded border p-4">
            {t.title}
          </li>
        ))}
      </ul>
    </main>
  );
}
