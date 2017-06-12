shortcodeplugin
===
Készítette: Ruzsinszki Gábor, webmaster442

Ez a bővítmény hasznos rövid kódoddal (shortcode) egészíti ki a wordpress környezetet. A jelenleg támogatott rövid kódok:

## [email]
A tag között elhelyezett szabványos formátumú e-mail címet olyan formában teszi közzé a weboldalon, hogy azzal a spam bot programok ne tudjanak mit kezdeni.

## [drivefolder-list] és [drivefolder-grid]
Kilistázza egy publikusan megosztott Google Drive mappa tartalmát. Két paraméter megadása szükséges. A List változat lista nézetet alkalmaz, míg a grid rács nézetet. Paraméterek:

 * id ->  A megosztandó mappa azonosító száma. A böngészőben ez úgy jelenik meg, mint: https://drive.google.com/drive/folders/[azonosító]
 * height -> A beágyazott mappa nézet magassága. Ha nincs megadva, alapértelmezetten 600px
 
## [archive]
Az oldalon közzétett összes bejegyzést kilistázza egy oldalon, létrehozva egy bejegyzés archívumot. Az archívum csoportosítása éves bontással jelenik meg

## [markdown]
Ezen blokk között elhelyezett kódot Markdown forráskódként értelmezi és ebből generál HTML kódot. A HTML generálásra a http://parsedown.org/ értelmező használódik.

## [subpages]
Egy oldalhoz tartozó összes al oldal kilistázása

## [loginlogout]
Be vagy kijelentkezési link, attól függően, hogy a felhasználó be van-e jelentkezve

## [registerlink]
Felhasználói regiszrációt lehetővé tévő link. Csak akkor jelenik meg, ha a jelenlegi felhasználó nincs belépve.

## [note]
Lábjlegyzet létrehozása. A lábjegyzetek az oldal végén összesítve mejelennek.

## Szintaxis kiemelés
Támogatott nyelvek: markdown, css, js, typescript, csharp, c, c++, f#, bash, git
```html
  <pre class="language-[nyelv]">
    <code>kód</code>
  </pre>
```
