import React from 'react';
import { render, screen } from '@testing-library/react';
import { userEvent } from '@storybook/test';
import PostForm from './PostForm';
import { Default } from './PostForm.stories';
import '@testing-library/jest-dom';

test('allows text input', async () => {
  render(<PostForm {...Default.args} />);
  const textarea = screen.getByPlaceholderText('Write a post...');
  await userEvent.type(textarea, 'Hello');
  expect(textarea).toHaveValue('Hello');
});
