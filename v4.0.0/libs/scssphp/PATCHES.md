# Local patches to the bundled scssphp

This directory vendors `scssphp/scssphp` **v1.12.1**. The tree carries local
patches that are **not** upstream. If you ever re-vendor or upgrade this
library, re-apply them or verify they are no longer needed.

## 1. PHP 8.4 — implicitly nullable parameters

**Applied:** 2026-08-14 · **Affects:** scssphp 1.x (all versions)

PHP 8.4 deprecated implicitly nullable parameters (`Type $arg = null`); the
nullable type must now be explicit (`?Type $arg = null`). On PHP 8.4 the
unpatched library emits `PHP Deprecated: ... Implicitly marking parameter
$x as nullable is deprecated` into `debug.log` on every compile.

Ten parameters across five files were made explicitly nullable:

| File | Method |
|---|---|
| `src/Warn.php` | `setCallback()` |
| `src/Node/Number.php` | `output()` |
| `src/Formatter.php` | `format()` |
| `src/Parser.php` | `__construct()` (`$cache`, `$logger`) |
| `src/Compiler.php` | `multiplyMedia()`, `pushEnv()`, `set()`, `get()`, `has()` |

This is behaviour-preserving: `?Type $x = null` and `Type $x = null` accept
exactly the same arguments. Only the deprecation notice changes.

**Why not just upgrade scssphp?**

- **1.13.0 does not fix this.** It was released 2024-08-17, before PHP 8.4, and
  ships the identical signatures.
- **Upstream will not fix it in 1.x.** Per the maintainer on
  [scssphp#797](https://github.com/scssphp/scssphp/issues/797): *"This is a
  known limitation of the version 1, as fixing those deprecations requires
  writing code that only supports PHP 7.1+ while the 1.x branch supports older
  versions. The solution is to migrate to version 2.0."*
- **2.x is a rewrite requiring PHP >= 8.1.** It removes `Compiler::compile()`
  (replaced by `compileString()` returning a `CompilationResult`) and drops the
  `Parser`, `Formatter`, `Node\Number` and `Warn` classes entirely. Migrating is
  worthwhile but is a separate, larger piece of work — and an 8.1 floor for the
  whole plugin is a product decision, not a bug fix.

Because the patch introduces PHP 7.1 syntax, the plugin's minimum PHP version
was raised to **7.1** in the same change (`RMP_MINIMUM_PHP_VERSION` in
`responsive-menu.php`, and `Requires PHP` in `readme.txt`). Keep those in sync.
