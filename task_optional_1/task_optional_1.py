#!/usr/bin/env python3
import base64

# Step 1: this Python template *itself* is encoded in Base64 and stored in `b64`
python_template = """#!/usr/bin/env python3
import base64

# Base64-encoded Python code (this script itself):
b64 = {b64!r}

# When run under PHP, the decoded Python will be echoed back:
php = "<?php\\n$py = '%s';\\nprint(base64_decode($py));\\n?>" % b64

print(php)
"""

# Compute the Base64 of the *final* Python code (i.e. the template with its own b64 embedded)
b64 = base64.b64encode(python_template.format(b64="").encode()).decode()
# Now regenerate the Python source with the correct b64 inserted:
python_code = python_template.format(b64=b64)

# Finally, emit the PHP quine:
#   - You can redirect this output into a .php file.
print(python_code)
