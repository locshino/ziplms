# Bot Scripts Directory

This directory contains development and testing scripts for the ZipLMS project. It is organized into the following subdirectories:

## Directory Structure

### 📁 `docs/`
**Purpose**: Documentation files, guides, and analysis reports
**When to use**: Place markdown files, documentation, guides, analysis reports, and any text-based documentation here.

**Examples**:
- Project authorization guides
- Permission testing documentation
- Solution summaries
- Usage guides
- Analysis reports

### 📁 `test/`
**Purpose**: Test files, debug scripts, utility scripts, and development tools
**When to use**: Place PHP test files, debug scripts, utility scripts, migration files, and any development/testing code here.

**Examples**:
- Unit tests (`*Test.php`)
- Integration tests
- Debug scripts (`debug_*.php`)
- Utility scripts (`check_*.php`, `fix_*.php`)
- Migration files
- Seeder files for testing
- Development tools

### 📁 `assets/`
**Purpose**: Static assets, data files, and resources
**When to use**: Place HTML files, SQL dumps, text files, images, and other static resources here.

**Examples**:
- HTML demonstration files
- SQL database dumps
- Text output files
- Static resources
- Data files

## Guidelines for AI Builders

When creating new files in this directory, follow these rules:

1. **Documentation files** (`.md`, guides, reports) → `docs/`
2. **Test files** (`.php` tests, debug scripts, utilities) → `test/`
3. **Static assets** (`.html`, `.sql`, `.txt`, data files) → `assets/`
4. **Root level**: Only keep `README.md` and `.gitignore`

## Git Ignore Policy

⚠️ **Important**: All files in this directory are ignored by Git (except `README.md` and `.gitignore` itself). This ensures that development scripts and temporary files don't get committed to the repository.

## Usage Examples

```bash
# Run a specific test
php test/run_simple_test.php

# Check permissions
php test/check_permissions.php

# Debug course logic
php test/debug_admin_course_logic.php
```

## Notes

- This directory is for development purposes only
- Scripts here should not be used in production
- Always test scripts in a development environment first
- Keep the directory organized by following the structure above