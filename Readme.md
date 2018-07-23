# PLUGIN PARRAINAGE SIMPLE POUR THELIA 2

Ce plugin permet aux clients qui parrainent de nouveaux clients d'obtenir une
réduction sur leur prochaine commande sous la forme d'un code promo standard qui
leur est expédié par mail.

Le filleuls peuvent obtenir une remise en pourcentage paramétrable sur leur première commande.

Contact et support: Franck Allimant / CQFDev - www.cqfdev.fr

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ParainageSimple.
* Activate and update settings it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require your-vendor/product-upsell-module:~1.0
```

## Usage

Go to module configuration and enter your settings :
- check "send sponsor code" if you want to register beneficiary id, firstname, lastname, email associated with sponsor id
  With this option you can have the invitation status invitation sent / accepted and also know how much a sponsor earn from each beneficiaries.
  This option work with a unique code in invitation instead of the sponsor email
- select the sponso discount type
- enter the sponso coupon amount 
- enter the minimum cart amount the sponsor must have to use it coupon
- enter the beneficiary discount in percent

## Hook
Hook on bo module.configuration - edit module conf
Hook on bo customer.edit - display beneficiary's sponsor
Hook on fo register.form-bottom  - enter a sponsor code or sponsor email on customer registration
Hook on fo account.top - display invitation form

## Loops
## [sponsorship]

loop that return sponsorship  values
if configuration to use invitation with code is not active
it des not return anything

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | filter by sponsorship id |
|**sponsor_id** | filter by sponsor id |
|**beneficiary_email** | filter by beneficiary id |
|**status** | filter by status |

### Output arguments

|Variable   |Description |
|---        |--- |
|ID    | sponsorship id |
|SPONSOR_ID    | sponsor id |
|BENEFICIARY_ID    | beneficiary id |
|BENEFICIARY_EMAIL    | beneficiary email |
|BENEFICIARY_FIRSTNAME    | beneficiary firstname |
|BENEFICIARY_LASTNAME    | beneficiary lastname |
|SPONSOR_COUPON_AMOUNT    | amount the sponsor earn with this sponsorship|
|BENEFICIARY_COUPON_AMOUNT    | amount the beneficiary earn with this sponsorship|
|STATUS    |  invitation status|

### Exemple

<table class="TableCart-inner">
  <colgroup>
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <col width="25%">
  </colgroup>
  <tbody>
  {loop type="sponsorship" name="sponsorship_loop"}
    <tr class="TableCart-line">
      <td class="TableCart">{$BENEFICIARY_FIRSTNAME} {$BENEFICIARY_LASTNAME}</td>
      <td class="TableCart">{$BENEFICIARY_EMAIL}</td>
      <td class="TableCart">{$STATUS}</td>
      <td class="TableCart text-primary">{$SPONSOR_COUPON_AMOUNT}</td>
   {/loop}
  </tbody>
</table>

## [parainagesimple]

return all sponsor customers with a coupon from sponsorship

### Input arguments

|Argument |Description |
|---      |--- |

### Output arguments

|Variable   |Description |
|---        |--- |
|CUSTOMER_ID    | customer id |
|COUPON_ID    | coupon id |

### Exemple

<table class="TableCart-inner">
  <colgroup>
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <col width="25%">
  </colgroup>
  <tbody>
  {loop type="parainagesimple" name="parainagesimple_loop"}
    <tr class="TableCart-line">
      <td class="TableCart">{$CUSTOMER_ID} {$COUPON_ID}</td>
     {/loop}
  </tbody>
</table>

## [info-parainagesimple]

return this module config

### Input arguments

|Argument |Description |
|---      |--- |

### Output arguments

|Variable   |Description |
|---        |--- |
|LABEL_PROMOTION    | promo label |
|TYPE_PARRAINAGE    | percent or amount |
|VALEUR_REMISE_FILLEUL    | beneficiary discount amount |
|VALEUR_REMISE_PARRAIN    | sponsor discount amount |
|MONTANT_ACHAT_MINIMUM    | minimum cart amount for sponsor to use coupon |

### Exemple

<table class="TableCart-inner">
  <colgroup>
    <col width="25%">
    <col width="25%">
    <col width="25%">
    <col width="25%">
  </colgroup>
  <tbody>
  {loop type="info-parainagesimple" name="info_parainagesimple_loop"}
    <tr class="TableCart-line">
      <td class="TableCart">{LABEL_PROMOTION}</td>
     {/loop}
  </tbody>
</table>

## Other ?

If you have other think to put, feel free to complete your readme as you want.
