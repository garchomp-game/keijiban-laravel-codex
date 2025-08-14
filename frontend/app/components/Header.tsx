'use client';
import { useRouter } from 'next/navigation';
import { logout } from '../../lib/api';

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
    <header style={{ padding: '1rem', borderBottom: '1px solid #ccc' }}>
      <button onClick={handleLogout}>Logout</button>
    </header>
  );
}
