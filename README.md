# 8fold Events

If this were going to be a project, what would it potentially look like.

Divide between UI & data. Divide between year, month, and day.

- /ui
  - /assets
    - events.scss
  - Grid.php
  - Year.php
  - Month.php
  - Day.php
- /data
  - Year.php
  - Month.php
  - Date.php
  - Event.php
- Events <- entry class

Depends on:

- Carbon
- Shoop
- Markup

Would be self-contained - JS as part of render.

Would need to let users know the folder structure requirements.

Would need to let users know of the URI pattern used (possibly be able to add a prefix).

Would need to be able to set the event splitter string.
