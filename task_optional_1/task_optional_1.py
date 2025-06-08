#!/usr/bin/env python3
import base64

# 1) Our Python self‐source, as a literal string:
code = '''#!/usr/bin/env python3
import base64

# 1) Our Python self-source, as a literal string:
code = {code!r}

# 2) Base64 encode our entire source:
b64 = base64.b64encode(code.encode("utf-8")).decode("ascii")

# 3) Emit the PHP stub (no closing tag):
print(f"<?php echo base64_decode('{b64}');")
'''

# 2) Fill in the placeholder so `code` really contains its own text:
full_py = code.format(code=code)

# 3) Base64-encode that and emit the one‐and‐only PHP file:
b64 = base64.b64encode(full_py.encode("utf-8")).decode("ascii")
print(f"<?php echo base64_decode('{b64}');")
