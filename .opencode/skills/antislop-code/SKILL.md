---
name: antislop-code
description: "Code comment hygiene for AI coding agents: remove generic AI-slop comments, keep the valuable ones."
allowed-tools: Read Write Edit Glob Grep
---
# antislop-code

> Anti Slop: Rules for AI Coding Agents. Code Comments skill

> Part of the antislop system. Read together with `antislop.md` (the core). This skill filters comments that read as generically AI (decorative, restating the obvious, stiff, loud) while preserving comments that carry real information.

## How to use this skill

- Load together with `antislop.md` whenever the task touches code comments.
- **Scope guardrail:** this skill only modifies comments. Never modify executable code, identifiers, imports, formatting, or logic.

## Comments That Add Nothing

### Decorative Separators

- **Tell:** banner comments with repeated characters or ALL CAPS labels: `// =======================` or `/* ---- ROUTES ---- */`
- **Why:** decoration is the message. Signals "AI made this" without adding information.
- **Fix:** replace with a single plain line, or remove if the label adds nothing (R-31).

### Restating the Obvious

- **Tell:** comment that repeats what the next line already shows: `// Initialize the variable` above `let count = 0`.
- **Why:** doubles the reading load without adding anything.
- **Fix:** remove and leave the code alone.

### Workflow Narration

- **Tell:** `// Step 1: Validate input`, `// Step 2: Process request`, `// Step 3: Return response`.
- **Why:** control flow is visible in the code itself.
- **Fix:** remove. If the flow is hard to follow, that is a structure problem.

### Empty Labels

- **Tell:** `// Main logic`, `// Core logic`, `// Helper function`, `// Note: This is important.`
- **Why:** names a category, not a fact.
- **Fix:** remove unless the label carries specific information.

### Vague Placeholders

- **Tell:** `// TODO: Improve this`, `// Future improvements`, `// Add more validation`.
- **Why:** names a feeling instead of a task.
- **Fix:** remove. Keep a TODO only when it names a specific task with enough context.

### Signature Echo

- **Tell:** JSDoc that repeats `@param price The price.` for a function whose name says it.
- **Why:** docs that echo the signature add length, not understanding.
- **Fix:** keep documentation that explains business rules, edge cases, assumptions, security implications.

### Decorative Emoji

- **Tell:** `// ✅ Validation` or `// 🚀 Performance`.
- **Why:** emoji is visual noise in code.
- **Fix:** replace with plain English, or remove if the label adds nothing.

### End Markers

- **Tell:** `} // end if`, `# End of function`.
- **Why:** the closing brace already ends the block.
- **Fix:** remove.

## How It Should Read

### Over-Explained Comment

- **Tell:** one comment running several lines, stacking reasons and history around a fact that fits in one line.
- **Why:** a person leaves a note, a generator writes a case.
- **Fix:** cut to the constraint alone: one line, two at most.

### Line-by-Line Narration

- **Tell:** a comment on every trivial statement: `// Initialize count`, `// Loop items`, `// Get item`.
- **Why:** when every line is commented, none matter.
- **Fix:** one concise comment per logical block instead of one per line.

### Stiff or Loud Wording

- **Tell:** "This function is responsible for validating whether the supplied credentials are valid..."
- **Why:** formal wording reads as generated.
- **Fix:** short, sentence-case lines in natural developer voice.

## Not a Ban (preserve these)

Never remove comments that explain:
- business logic and intent
- architectural decisions
- security considerations
- performance trade-offs
- workarounds and edge cases
- API contracts

## Code Comment Checklist

- [ ] Every comment adds information the code does not already show (R-31)
- [ ] No decorative separators, ALL CAPS banners, box-drawn headers
- [ ] No restating the obvious line, declaration, or signature
- [ ] No step-by-step workflow narration
- [ ] No empty labels or vague TODOs
- [ ] No decorative emoji or end markers
- [ ] One comment per logical block, not one per line
- [ ] Every comment one line, or two only when the second carries a new fact
- [ ] Remaining comments read short, natural, sentence case
- [ ] Scope held: only comments changed, code untouched
