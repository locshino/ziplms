# **Contributing to ZipLMS**

First off, thank you for considering contributing to ZipLMS\! It's people like you that make open source great.

When contributing to this repository, please first discuss the change you wish to make via an issue, email, or any other method with the owners of this repository before making a change.

Please note we have a code of conduct, please follow it in all your interactions with the project.

## **How Can I Contribute?**

### **Reporting Bugs**

* Ensure the bug was not already reported by searching on GitHub under [Issues](https://github.com/locshino/ziplms/issues).  
* If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/locshino/ziplms/issues/new). Be sure to include a **title and clear description**, as much relevant information as possible, and a **code sample or an executable test case** demonstrating the expected behavior that is not occurring.

### **Suggesting Enhancements**

* Open a new issue to start a discussion about your new idea. This is crucial as it allows us to align on the feature before you put a lot of effort into it.  
* Clearly describe the proposed enhancement, why it's needed, and provide examples of how it would work.

### **Pull Request Process**

1. **Fork the repository** and create your branch from develop.  
2. **Set up your development environment** by following the instructions in README.md.  
3. **Make your changes.** Ensure your code follows the project's coding standards.  
4. **Add tests** for your new feature or bug fix. We aim for a high test coverage.  
5. **Run all tests** to ensure everything is passing:  
   sail php artisan test

6. **Check and fix code style** using Laravel Pint:  
   \# Check for issues  
   sail pint \--test

   \# Automatically fix issues  
   sail pint

7. **Update the README.md** or other relevant documentation with details of changes to the interface, this includes new environment variables, new commands, etc.  
8. **Commit your changes** using a descriptive commit message that follows our commit convention (see below).  
9. **Push your branch** to your fork and **open a pull request** to the develop branch of the main repository.  
10. Ensure the pull request description clearly describes the problem and solution. Include the relevant issue number if applicable.

## **Git Commit Conventions**

We follow the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) specification. This helps in generating automated changelogs and makes the commit history easier to read.

Please format your commit messages as follows:

\<type\>(\<scope\>): \<subject\>

\<body\>

\<footer\>

* **type**: feat (new feature), fix (bug fix), docs (documentation), style (code style), refactor, test, chore (build process, package manager).  
* **scope** (optional): The part of the codebase your change affects (e.g., courses, auth, exams).  
* **subject**: A short, imperative-tense description of the change.

**Example:**

feat(auth): add two-factor authentication support

## **Code Style**

This project uses **Laravel Pint** to enforce a consistent code style based on the PSR-12 standard. Please ensure you run sail pint before committing your changes.

## **Questions?**

Feel free to open an issue or contact one of the project maintainers if you have any questions. Thank you for your contribution\!
