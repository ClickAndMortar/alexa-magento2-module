# Alexa Magento 2 Module - C&M

> Alexa Magento 2 Module for [Magento 2](https://magento.com/home-page) and
> [Amazon Alexa](https://developer.amazon.com/alexa).

`Alexa Magento 2 Module` allows to retrieve data from a Magento 2 website
 using an Amazon Alexa device (such as an Amazon Echo).

> User: "Alexa, demande à magento le nombre de clients connectés"\
> Alexa: "Le nombre de clients et visiteurs en ligne est de 10."

## Requirements

 |                                            | Version |
 | ------------------------------------------ | ------- |
 | PHP                                        | `>=7.2` |
 | [Magento](https://magento.com/home-page)   | `>=2.3` |

## Install

### Deploy Alexa skill

In order to communicate with the Magento module, your Alexa device must have access to
[Alexa skill Magento 2](https://github.com/ClickAndMortar/alexa-magento2-skill).

### Install Magento 2 module

```
composer require click-and-mortar/alexa-magento2-module
php bin/magento setup:upgrade
```

## Use

Using
[Alexa Skills Kit CLI](https://developer.amazon.com/docs/smapi/quick-start-alexa-skills-kit-command-line-interface.html)
to simulate the skill via interactive dialog with Alexa.

### List of intents

- **Get average order**

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento le panier moyen
  Alexa >  Le panier moyen est de 119,75 € depuis le lancement du site.
```

- **Get bestsellers**

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento les produits les plus vendus
  Alexa >  Les 3 produits les mieux vendus sont : Erika Running Short-32-Red avec un nombre de 10, Erika Running Short-31-Red avec un nombre de 1, Erika Running Short-28-Red avec un nombre de 1.
```

- **Get customers now online**

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento le nombre de clients connectés
  Alexa >  Le nombre de clients et visiteurs en ligne est de 10.
```

- **Get orders count for period**

See
[here](https://github.com/ClickAndMortar/alexa-magento2-skill/blob/master/models/fr-FR.json#L87)
for available periods.

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento le nombre de commandes
  Alexa >  Il y a 5 commandes depuis le lancement du site.
```

```bash
  User  >  demande à magento le nombre de commandes du mois
  Alexa >  Le nombre de commandes est de 4 pour le mois en cours.
```

- **Get turnover for period**

See
[here](https://github.com/ClickAndMortar/alexa-magento2-skill/blob/master/models/fr-FR.json#L87)
for available periods.

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento le chiffre d'affaires
  Alexa >  Le chiffre d'affaires est de 479,00 € depuis le lancement du site.
```

```bash
$ ask dialog --locale fr-FR
  User  >  demande à magento le chiffre d'affaires pour les sept derniers jours
  Alexa >  Vous avez un chiffre d'affaires pour les sept derniers jours de 119,00 €.
```

## Roadmap

- [ ] Add authentication to the API
- [ ] Handle locales as currently only `fr_FR` is supported
- [ ] [Manually verify that the request was sent by Alexa](https://developer.amazon.com/docs/custom-skills/host-a-custom-skill-as-a-web-service.html#manually-verify-request-sent-by-alexa)
- [ ] [Verify that the request came from your skill](https://developer.amazon.com/docs/custom-skills/handle-requests-sent-by-alexa.html#request-verify)
