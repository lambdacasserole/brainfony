brainfony
=========

A super-serious web framework for dedicated developers.

![Brainfony](data:image/png;base64,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)

