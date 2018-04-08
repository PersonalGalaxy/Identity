# Identity

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/PersonalGalaxy/Identity/build-status/develop) |

Model to manage logins via password, or combined via a 2fa code.

## Installation

```sh
composer require personal-galaxy/identity
```

## Usage

The only entry point to use the model are the [commands](src/Command), you should use a [command bus](https://github.com/innmind/commandbus) in order to bind the commands to their handler.

You also need to implement the repository [interface](src/Repository/IdentityRepository.php) in order to persist the identities.
