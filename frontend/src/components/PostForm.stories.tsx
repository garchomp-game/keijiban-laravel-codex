import type { Meta, StoryObj } from '@storybook/nextjs';
import PostForm from './PostForm';

const meta: Meta<typeof PostForm> = {
  title: 'Components/PostForm',
  component: PostForm,
};
export default meta;

type Story = StoryObj<typeof PostForm>;

export const Default: Story = {
  args: {
    threadId: 1,
  },
};
