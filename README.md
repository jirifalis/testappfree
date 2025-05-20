# Database Structure

## ACL tables

### users

- `id` (int) - Primary key, auto-increment
- `username` (varchar(180)) - Unique username
- `password` (varchar(255)) - ...
- ...

### resources

- `id` (int) - Primary key, auto-increment
- `name` (varchar(100)) - Unique resource name

### groups

- `id` (int) - Primary key, auto-increment
- `name` (varchar(100)) - Unique group name

### users_groups

- `user_id` (int) - Foreign key to users.id
- `group_id` (int) - Foreign key to groups.id
  
Primary key: (user_id, group_id)


### permissions

- `group_id` (int) - Foreign key to groups.id
- `resource_id` (int) - Foreign key to resources.id
- `permission` (varchar(100)) - Permission/Restriction

Primary key: (group_id, resource_id)


---------------------------

# Key components

- `ResourceInterface`
- `AccessControlTrait`
- `ResourceNameTrait`
- `EventListener/AccessValidation`
- `Exception/ForbiddenException`
- `AclRepository`

---------------------------

# Návrh řešení

## Popis

Každý uživatel může být přiřazený v jedné a více skupinách, každá skupina může mít nastavení permission na jednotlivé resources. 

## Případy užití

Navrhl jsem řešení pro tyto případy užití:

### Na úrovni controlleru

Vytvořil jsem `AccessValidation` event listener na úrovni frameworku, který naslouchá `kernel.controller` event. V podstatě než se spustí jakákoli akce na controlleru, tak se spustí AccessValidation.

Udělal jsem řešení, že pokud controller implementuje `ResourceInterface`, tak se automaticky vezme název resource z controlleru a ověří se, že aktuálně přihlášený uživatel má přístup k tomuto resource. 

Je k tomu potřeba do controlleru vložit `ResourceNameTrait`, který obsahuje dodatečnou informaci pro DI a funkci `getResourceName` vyžadovanou `ResourceInterface`.

Funkce `getResourceName` vrací název resource.

```php
class AlfaController extends AbstractController implements ResourceInterface // <---- HERE
{
    
    use ResourceNameTrait; // <---- HERE

    #[Route("/{id}")]
    public function index(int $id): JsonResponse
    {
        // ... already validated access to 'alfa' resource ...
        
        return new JsonResponse(['resource' => $this->getResourceName(), 'id' => $id]);
    }
    // ...
}
```

### Na úrovni controlleru - manuálně

Vytvořil jsem `AccessControlTrait`, který obsahuje informaci pro DI a funkci `isAllowed(string $resource)`, která vrací jestli přihlášený uživatel má přístup k danému resource. Stačí 

Stačí pouze vložit `use AccessControlTrait;` a na úrovni třídy je dostupná funkce `isAllowed`, včetně funkcí `forbiddenJsonResponse` a `forbiddenResponse`, které vrací response s chybou.

```php

#[Route("/resource/multi")]
class MultiResourceController extends AbstractController
{
    use AccessControlTrait; // <---- HERE

    #[Route("/{id}")]
    public function index(int $id): JsonResponse
    {
        if (!$this->isAllowed('alfa') || // <----  HERE
            !$this->isAllowed('beta')) {
            return $this->forbiddenJsonResponse();
        }

        // ... do some stuff

        return new JsonResponse(['resource' => 'multi', 'id' => $id]);
    }
}

```

### Na úrovni repository nebo jakékoli služby

Trait `AccessControlTrait` lze vložit do jakékoli třídy a automaticky je k dispozici funkce `isAllowed`.

```php

class InvoiceRepository extends ServiceEntityRepository
{
    // ...    
    
    use AccessControlTrait; // <----  HERE

    public function loadByCompanyName(string $name): ?Invoice
    {
        if(!$this->isAllowed('invoice')){ // <----  HERE
            throw new ForbiddenException('invoice'); 
        }
        return $this->createQueryBuilder('i')
            ->where('i.company_name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    // ...
}

```
---------------------------

Pozn: 
- v databázi jsou u skupin uloženy pouze povolené oprávnění na resources. Udělal jsem to tak, protože oprávnění jsou stylem POVOLENO/ZAKÁZÁNO. Co je v databázi, tak je povoleno, jinak je zakázáno.
- Má to výhodu, že je logika snadnější a nemusí se řešit defaultní stav nebo init dat v databázi. Také je v databázi méně záznamů.
- Funkce `findAccessibleResourcesByUserId` v `AclRepository` načítá dostupný resources pro specifikovaného uživatele. Je to dočasná implementace, která čistým sql načte dostupný resources. 
- Na úrovni AclRepository je několik úrovní cache, jednak db server má sql cache, na úrovni frameworku lze nastavit cache pro ORM. Alternativně bych udělal custom cache v závislosti na potřebách a po bližším prozkoumání problému a reálného užití.
- Stejně tak jsem neoptimalizoval načítání provázanách dat.
- Uživatelů a provoz je podle mě malý, tak není nutné navrhovat extra cache řešení.
- Entity, které se posílají na frontend implementují interface `JsonSerializable` a funkci `jsonSerialize`. Která připravuje data pro frontend.

---------------------------




---------------------------

# Moje řešení

## Proč jsem zvolil svoje řešení

S nette nepracuji, tak jsem pro test zadání zvolil Symfony.
Doctrine ORM jsem využil také proto, že při práci s daty preferuji DataMapper oproti ActiveRecord.

Symfony i Doctrine má standardní knihovnu pro ACL. Ty jsem nevyužil. Řekl jsem si, že napíšu nějaký kód do test zadání a zároveň pokud řešení může být jednoduché, tak lepší udělat vlastní, než mít na projektu další externí závislost o kterou je potřeba se starat.


## Backend

Výhoda mého řešení je, že na to jak jsem problém pochopil, tak to řeší automaticky hromadu věcí za vývojáře. Přidám jeden řádek do třídy, kde chci aby to fungovalo a funguje to. Lze bezpečně nastavit na úrovni controllerů nebo služeb a nemusím na to myslet, zároveň je řešení flexibilní, že lze použít i v jiných třídách.

Alternativy k mému řešení jsou již existující řešení. Věřím, že s Nette/Security/Permission nebo Symfony/ACL, by šlo dále pracovat.

`Skupiny` zní hodně jako `role`, které se používají, ale role jsem ponechal pro vyšší level řízení, např "Admin", "User", "FreeUser" apod.


## Databáze

V závislosti jak je na projektu vyřešené logování bych zvážil přidat datum vytvoření/smazání do všech tabulek, případně id uživatele kdo akci udělal. Nebo to logovat externě. V současném řešení jsem tyto informace vypustil, ale standardně tyto infroamce v systému sleduji.


## Rozšížení

Funcionalita pro repository lze rozšířit dále napojením event listeneru na ORM na událost např "preLoad" (než se sáhne na DB), případně více provázat s ORM, aby ORM automaticky filtroval data.

Také lze rozšířit pro vícestupňové oprávnění, ne jen permission/restriction, ale read/write/delete, apod. Pak bych funkci `isAllowed` rozšířil o typ akce nebo bych definoval pomocné funkce jako `isReadable`, `isWritable`, apod. v závislosti kde by to bylo použíte a jak by se s tím pracovalo.


# Frontend

Udělal bych endpointy, které vrací uživatele a skupiny již s dodatečnými informacemi. U uživatelů (skupiny, do kterých patří) a u skupin (permission na resources co má). Pro uživatele bych udělal endpoind se stránkováním, budou tisíce uživatelů (a na backendu bych dodatečný data načítal jen k uživatelům, které se zobrazí.. načítal bych z cache).

Pro skupiny není potřeba dělat stránkování, pokud bude cca 20 skupin.
Udělal bych další endpointy pro načtení resources.

Předpokládám, že člověk, který bude nastavovat oprávnění, může vidět seznam všech resources a skupin.

Ukládání bych udělal tak, že na serveru budou update endpointy, které přijmou list ID (backend si načte aktuálního uživatele/skupinu a aktualizuje přiřazení.. odmaže ty co nemají být aktivní, doplní ty co mají být aktivní a nejsou ještě nastavené)

Je to agresivnější řešení, že přiřazení co nepřijdou z frontendu, tak se smažou. Přišlo mi to v tomto případě lepší, pokud budou nějaké pozůstatky v databázi, tak je to s nimi pročistí.
Bezpečnější řešení by bylo mazat explicitně vybrané přiřazení pro smazání. To je bezpečnější a zároveň umožňuje řešení, že člověk co to nastavuje, nevidí na všechny resources/skupiny.


# Frontend 2 (více obecně)

V závilosti jak je nastavený projekt, jestli se vše ukládá až při stisknutí "save" nebo se vše ukládá interaktivně ihned "onchange", tak bych zlovil vhodné řešení.

Pokud "save", tak bych navrhl řešení, viz výše, že FE posílá na BE list ID a na BE proběhne aktualizace.
Pokud "onsave", tak bych navrhl řešení explicitního přiřazení/smazání.

Pokud bych musel navrhnout novou strukturu komunikace mezi FE a BE, tak bych udělal třídy Users, Groups, Resources a přidal jim cache vrstvu, validati response a error handling.

Počítal bych, že z BE dostanu pouze data, ke kterým mám přístup a se kterými mohu pracovat. Pokud bych na některé stránky/resource měl přístup jen v závislosti na nastavení, tak bych si stahoval informace o přístupných sekcích jako jeden z prvních init requestů na server.

Vše bych řešil přístupem optimistických updatu. Na FE bych změny stavu propisoval ihned a v pozadí komunikoval s BE (a teprve v případě problému bych informoval uživatele).
Hodně bych se soustředil na interakci s uživatelem, aby všechny prvky měly hover, aby byl loading pokud se načítají data, apod. Aby aplikace působila interaktivně a rychle.



## Prototyp

Při návrhu řešení jsem si udělal prototyp.
(Prototyp neřeší cache, zabezpečení, design ani ux).

Nahrál jsem prototyp zde:

https://test.jirifalis.cz/frontend/

Endpointy pro náhled:
- https://test.jirifalis.cz/public/admin/resources
- https://test.jirifalis.cz/public/admin/groups
- https://test.jirifalis.cz/public/admin/users
- https://test.jirifalis.cz/public/admin/user/1
- https://test.jirifalis.cz/public/admin/group/1


## Source

Vybrané soubory BE jsem nahrál do repozitáře. FE kód jsem nabouchal rychle ve vue.js+typescript, použil jsem router, pár komponent a fetch pro komunikaci se serverem. 
