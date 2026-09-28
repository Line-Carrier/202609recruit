# Backend Developer – tekninen testitehtävä

## Tausta

Olet liittymässä kehitystiimiin, joka ylläpitää PHP-pohjaista REST API -palvelua.

Sovellus käyttää:
- PHP 8.5
- Symfony 7.4 LTS
- API Platform 4
- Doctrine ORM
- PostgreSQL
- LexikJWTAuthenticationBundle
- Docker

Saat käyttöösi valmiiksi alustetun projektin. Tarkoituksena ei ole rakentaa sovellusta alusta asti, vaan jatkokehittää olemassa olevaa sovellusta.

**Arvioitu työaika: 1–2 tuntia.**

Testejä ei tarvitse kirjoittaa.

## Tehtävä

Sovelluksessa hallitaan asiakkaita ja heidän tilauksiaan. Projektissa on valmiiksi:
- User
- Customer
- Order

Customer voi sisältää useita Order-tilauksia.

JWT-autentikointi on valmiiksi osittain konfiguroitu LexikJWTAuthenticationBundlen avulla.

### 1. Asiakkaan tilausten API

Toteuta API, jonka avulla voidaan hakea tietyn asiakkaan tilaukset:

`GET /api/customers/{id}/orders`

Vastauksen tulee sisältää asiakkaan tilaukset JSON-muodossa.

Tutustu ensin olemassa oleviin Entityihin, relaatiototeutuksiin ja API Platform -rakenteeseen.

### 2. Tilausten suodatus

Lisää mahdollisuus suodattaa asiakkaan tilauksia tilan perusteella:

`GET /api/customers/123/orders?status=pending`

Mahdollisia tiloja ovat:
- pending
- processing
- completed
- cancelled

Suodatuksen tulee tapahtua tietokantatasolla, ei hakemalla kaikkia tilauksia PHP:lle ja suodattamalla niitä vasta sen jälkeen.

Hyödynnä tarvittaessa olemassa olevaa Repository-rakennetta.

### 3. Autentikointi

Projektissa on LexikJWTAuthenticationBundle sekä käyttäjäautentikointi valmiiksi osittain toteutettuna.

Tutki nykyinen autentikointiin liittyvä toteutus ja varmista, että tilausten API on käytettävissä vain autentikoidulle käyttäjälle.

JWT:n muodostamiseen liittyvää toiminnallisuutta ei tarvitse rakentaa uudelleen.

### 4. Virhetilanteet

Huomioi esimerkiksi:

`GET /api/customers/999999/orders`

Jos asiakasta ei ole olemassa, API:n tulee palauttaa asianmukainen HTTP-status eikä tyhjää onnistunutta vastausta.

### 5. Ylläpidettävyys

Toteutuksen tulee sopia olemassa olevan projektin rakenteeseen. Älä rakenna toiminnallisuutta tarpeettomasti kokonaan uudella tavalla, jos projektissa on jo olemassa oleva käytäntö.

Kiinnitä huomiota erityisesti:
- Symfony-palveluihin
- Dependency Injectioniin
- Doctrine Entity -relaatioihin
- Repository-rakenteeseen
- API Platformiin
- HTTP-statuskoodeihin
- autentikointiin
- koodin selkeyteen

## Laravel – lyhyt teoriaosuus

Vastaa lyhyesti seuraaviin kysymyksiin. Koodia ei tarvitse kirjoittaa.

1. Symfonyssa Dependency Injection perustuu Service Containeriin. Miten vastaava asia toteutetaan Laravelissa?

2. Sinulla on Laravelissa Customer ja Order, joista yhdellä Customerilla voi olla useita Ordereita. Miten määrittelisit relaation Laravel Eloquentissa?

3. Miten toteuttaisit Laravelissa endpointin `GET /api/customers/{id}/orders`? Mitä Laravelin komponentteja käyttäisit?

## Bonus

Jos pääkohdat ovat valmiit ja aikaa jää, voit:
- parantaa API:n virheenkäsittelyä
- parantaa API-dokumentaatiota
- tehdä toteutuksesta uudelleenkäytettävämmän
- huomioida mahdollisen N+1-kyselyongelman
- tehdä muun perustellun parannuksen, jonka huomaat projektia tutkiessasi

Bonus ei ole pakollinen.

## Palautus

Palauta muuttamasi projekti annetun repositoryn kautta.

Erillistä dokumentaatiota ei tarvitse kirjoittaa. Jos teet jonkin ratkaisun, joka ei ole ilmeinen, voit kommentoida sitä lyhyesti koodissa tai README-tiedostossa.

Tärkeintä ei ole mahdollisimman suuri määrä koodia, vaan se, että ratkaisu sopii olemassa olevaan projektiin ja on helposti ylläpidettävissä.
