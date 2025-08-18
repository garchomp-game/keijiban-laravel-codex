"use client";
import { useEffect, useState } from "react";
import Link from "next/link";
import { api, logout } from "../lib/api";
import type { components } from "../lib/api-types";

type Thread = components['schemas']['Thread'];
type User = components['schemas']['User'];

export default function Home() {
  const [threads, setThreads] = useState<Thread[]>([]);
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    api.GET("/threads").then((r) => {
      if (r.data) setThreads(r.data.data);
    });
    api.GET("/user").then((r) => {
      if (r.data) setUser(r.data.data);
    });
  }, []);

  const handleLogout = async () => {
    try {
      await logout();
      setUser(null);
    } catch (e) {
      console.error(e);
    }
  };

  return (
    <main className="p-6">
      {!user && (
        <p className="mb-4">Welcome! Please login to continue.</p>
      )}
      <div className="mb-4 text-right">
        {user ? (
          <>
            <span className="mr-4">Hello, {user.name}</span>
            <button
              onClick={handleLogout}
              className="text-blue-600 hover:underline"
            >
              Logout
            </button>
          </>
        ) : (
          <Link href="/login" className="text-blue-600 hover:underline">
            Login
          </Link>
        )}
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
