"use client";
import { useRouter } from "next/navigation";
import { logout } from "../../lib/api";

export default function Header() {
  const router = useRouter();

  const handleLogout = async () => {
    try {
      await logout();
      router.push('/login');
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <header className="flex justify-end border-b p-4">
      <button
        onClick={handleLogout}
        className="text-sm text-gray-600 hover:text-gray-900"
      >
        Logout
      </button>
    </header>
  );
}
