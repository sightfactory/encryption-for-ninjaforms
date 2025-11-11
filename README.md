# Encryption for Ninja Forms

**Plugin Name:** Encryption for Ninja Forms
**Description:** Encrypts selected fields on form submission in Ninja Forms for enhanced data security.
**Version:** 1.0.0
**Author:** [Sightfactory](https://www.sightfactory.com)

---

## Features

This WordPress plugin provides the following security and utility features for Ninja Forms:

* **Field Encryption:** Automatically encrypts the value of any field whose **Field Key** contains the string `encrypted` upon form submission.
* **Decryption for Export:** Decrypts the stored value when exporting Ninja Forms submissions (e.g., to a CSV file) via the `ninja_forms_subs_export_pre_value` filter.
* **Custom Encrypted Field Type:** Registers a custom input field named **"Encrypted"** (internal key: `vwpnfencryption`) that acts like a standard textbox.
