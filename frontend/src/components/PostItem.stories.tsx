import type { Meta, StoryObj } from '@storybook/nextjs';
import PostItem, { Post } from './PostItem';

export const SamplePost: Post = {
  id: 1,
  body: 'Hello Storybook',
  user: { id: 1, name: 'Alice', email: 'alice@example.com', created_at: '', updated_at: '' },
  thread_id: 1,
  created_at: '',
  updated_at: '',
  reactions: { like: 2, dislike: 1 },
  myReaction: null,
};

const meta: Meta<typeof PostItem> = {
  title: 'Components/PostItem',
  component: PostItem,
};
export default meta;

type Story = StoryObj<typeof PostItem>;

export const Default: Story = {
  args: {
    post: SamplePost,
  },
};
