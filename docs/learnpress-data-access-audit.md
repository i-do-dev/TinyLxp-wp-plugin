# LearnPress Data Access Audit

Date: 2026-03-29
Status: Phase 1 started, first API-first migration applied in repository lookup methods.

## Classification Rules

- A: Replaceable with LearnPress core API or class without behavior regression.
- B: Keep in repository abstraction for now (core API gap or schema compatibility need).
- C: Unavoidable direct SQL currently outside repository. Needs containment and hardening.

## Inventory Matrix

| File | Method or Area | Current Access Pattern | Class | Replacement Target | Risk Notes |
|---|---|---|---|---|---|
| lms/repositories/class-learnpress-section-repository.php | get_course_id_by_item_id | LearnPress tables with SQL fallback | B -> A path started | Added guarded LearnPress API-first probes before SQL fallback | LearnPress API shape varies by version; keep fallback active |
| lms/repositories/class-learnpress-section-repository.php | get_section_name_by_item_id | LearnPress tables join | B -> A path started | Added guarded LearnPress API-first probes before SQL fallback | Section object fields differ across LP versions |
| lms/class-learnpress-lesson-extension.php | resolve_course_id_for_lesson | repository lookup from lesson item relation | B | Keep repository entrypoint, now API-first internally | Launch and save critical path, avoid broad rewrite |
| lms/lms-rest-apis/courses.php | get_lxp_sections/get_lxp_course_section_lessons/get_lxp_lessons_by_course | repository calls | B | Keep repository wrapper and improve internals | Low risk if return shape unchanged |
| lms/lms-rest-apis/lms-rest-api.php | course section CRUD helpers | repository calls on custom section table shape | B | Keep repository, evaluate LP DB class parity later | High coupling to existing UI payloads |
| lms/templates/tinyLxpTheme/page-learner-lesson.php | section_name resolution | repository call from template | B | Keep callsite, repository now API-first | Template expects string only |
| includes/widgets/lxp-student-grade-summary-widget.php | section_title resolution | repository call from widget | B | Keep callsite, repository now API-first | Cached response should remain string |
| lms/lms-rest-apis/lms-rest-api.php | score and trek event queries | direct SQL on plugin custom tables | C | Move to dedicated repository classes + prepared queries | Not LearnPress data but has SQL safety risk |
| lms/lms-rest-apis/assignment-submissions.php | tiny_lms_grades update query | direct SQL string concatenation | C | Replace with prepare and repository wrapper | Write path, SQL injection risk |

## Implemented In This Pass

1. Repository now attempts LearnPress API or DB class resolution before SQL fallback in:
   - get_course_id_by_item_id
   - get_section_name_by_item_id
2. Fallback SQL behavior remains unchanged to preserve compatibility.

## Next Implementation Steps

1. Convert C paths in assignment-submissions and lms-rest-api grade or event methods to prepared queries.
2. Add a dedicated tiny_lms_grades repository for read and write helpers.
3. Add a trek events repository to isolate query construction from REST callbacks.
4. Add quick regression checks for:
   - lesson admin save of LTI metadata
   - REST lesson insert with LTI metadata
   - learner lesson embed launch
