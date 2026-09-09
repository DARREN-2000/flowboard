import React, { useState } from 'react';
import { Button } from '../ui/Button';
import { TextArea } from '../ui/TextArea';
import { useAiSuggestions } from '../../hooks/useAiSuggestions';

export const AiGeneratePanel: React.FC<{ projectId: string }> = ({ projectId }) => {
  const [prompt, setPrompt] = useState('');
  const { generateTasks, isGenerating } = useAiSuggestions(projectId);

  return (
    <div className="bg-primary-50 p-4 rounded-lg border border-primary-200 mb-6">
      <h3 className="text-primary-800 font-semibold mb-2 flex items-center gap-2">
        <span role="img" aria-label="sparkles">✨</span>
        Generate Tasks with AI
      </h3>
      <TextArea
        value={prompt}
        onChange={(e) => setPrompt(e.target.value)}
        placeholder="Describe the feature or epic, e.g., 'Add user authentication with email and Google OAuth'"
        className="mb-3 bg-white"
      />
      <div className="flex justify-end">
        <Button 
          variant="primary" 
          onClick={() => generateTasks(prompt)}
          isLoading={isGenerating}
          disabled={!prompt.trim()}
        >
          Generate Tasks
        </Button>
      </div>
    </div>
  );
};
