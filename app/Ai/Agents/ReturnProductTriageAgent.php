<?php

namespace App\Ai\Agents;

use App\Enums\DamageLevel;
use App\Enums\RecommendedAction;
use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\UseCheapestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::OpenAI)]
#[UseCheapestModel]
class ReturnProductTriageAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'TXT'
You are an experienced customer support triage specialist for an e-commerce company.
Your role is to analyze product return requests and help the customer support team determine the most appropriate next steps.

Based on the customer's stated reason for the return, their description of the problem, and the available product information, you must:

- Classify the request into the most appropriate support category.
- Assess the severity of any reported physical damage.
- Determine the priority with which the support team should handle the request.
- Produce a concise summary of the customer's complaint.
- Recommend the most appropriate next action for the support team.
- Determine whether the request requires human review.

Follow these rules:

- Base your assessment exclusively on the information provided in the request and product details.
- Distinguish between facts explicitly reported by the customer and conclusions inferred from their description.
- Do not assume that a product is defective or physically damaged beyond what the available information supports.
- If the description is insufficient or ambiguous, use the appropriate unknown or general category and recommend requesting more information or human review.
- Treat potential safety concerns, significant damage, and uncertain cases requiring judgment with appropriate caution.
- Do not promise refunds, approve returns, authorize replacements, or claim that any action has already been taken.
- Your recommended action is advice for the support team, not an executed business decision.
- Be consistent in your classification and prioritization across similar cases.
- Return a result that follows the structured output schema exactly.

TXT;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'category' => $schema->string()
                ->enum(array_column(ReturnCategory::cases(), 'value'))
                ->description('The support category that best matches the return request.')
                ->required(),
            'damage_level' => $schema->string()
                ->enum(array_column(DamageLevel::cases(), 'value'))
                ->description('The severity of any reported physical damage, or "unknown" if it cannot be determined.')
                ->required(),
            'priority' => $schema->string()
                ->enum(array_column(ReturnPriority::cases(), 'value'))
                ->description('How urgently the support team should handle the request.')
                ->required(),
            'summary' => $schema->string()
                ->description("A concise summary of the customer's complaint.")
                ->required(),
            'recommended_action' => $schema->string()
                ->enum(array_column(RecommendedAction::cases(), 'value'))
                ->description('The most appropriate next action for the support team.')
                ->required(),
            'requires_human_review' => $schema->boolean()
                ->description('Whether the request should be reviewed by a human before acting on it.')
                ->required(),
        ];
    }
}
