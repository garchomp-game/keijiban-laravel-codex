import React from 'react';
import { render, screen } from '@testing-library/react';
import PostItem from './PostItem';
import { Default } from './PostItem.stories';
import '@testing-library/jest-dom';

test('renders post content', () => {
  render(<PostItem {...Default.args} />);
  expect(screen.getByText('Hello Storybook')).toBeInTheDocument();
  expect(screen.getByText('👍 2')).toBeInTheDocument();
  expect(screen.getByText('👎 1')).toBeInTheDocument();
});
