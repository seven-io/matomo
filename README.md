<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS Reports for Matomo</h1>

<p align="center">
  Receive Matomo analytics reports as SMS via the seven gateway. A drop-in SMS provider for the Matomo <em>Personal Email Reports</em> feature.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <a href="https://plugins.matomo.org/Seven"><img src="https://img.shields.io/badge/Matomo-Marketplace-orange" alt="Matomo Marketplace" /></a>
  <img src="https://img.shields.io/badge/PHP-7.2%2B-purple" alt="PHP 7.2+" />
</p>

---

## Features

- **SMS Reports** - Deliver scheduled Matomo reports as SMS instead of email
- **Phone Number Validation** - Built-in confirmation flow for new recipient numbers
- **Standard Matomo Plugin** - Installs from the Matomo Marketplace

## Prerequisites

- A self-hosted [Matomo](https://matomo.org/) installation
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

### Marketplace (recommended)

Install via [plugins.matomo.org](https://plugins.matomo.org/Seven) directly from the Matomo admin under **Marketplace**.

### Manual

1. Download the [latest release](https://github.com/seven-io/matomo/releases/latest/download/seven-matomo-latest.zip).
2. Extract the contents into `/plugins` of your Matomo installation.
3. Open **Settings > System > Plugins** and activate **Seven**.

## Configuration

1. [Add a new SMS provider](https://raw.githubusercontent.com/seven-io/matomo/master/screenshots/add_sms_provider.png) using your seven API key.
2. [Add a new phone number](https://raw.githubusercontent.com/seven-io/matomo/master/screenshots/add_phone_number.png).
3. [Validate the phone number](https://raw.githubusercontent.com/seven-io/matomo/master/screenshots/validate_phone_number.png) via the confirmation SMS.
4. [Create a report](https://raw.githubusercontent.com/seven-io/matomo/master/screenshots/create_report.png) with *Type = SMS*.
5. Click **Send Report now** in the [Personal Email Reports overview](https://raw.githubusercontent.com/seven-io/matomo/master/screenshots/reports_overview.png) to verify delivery.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/matomo/issues).

## License

[MIT](LICENSE)
