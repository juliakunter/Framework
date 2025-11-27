
1.	Wie sieht der Konstruktor in PHP Klassen aus?
a.	Der Konstruktor ist eine spezielle Methode, die automatisch ausgeführt wird, sobald ein Objekt erzeugt wird.
In PHP heißt sie immer __construct().

2.	Wozu dient die „Variable“ $this?
a.	$this verweist innerhalb eines Objekts auf genau dieses aktuelle Objekt.
Man verwendet $this, um Eigenschaften und Methoden der eigenen Klasse aufzurufen.

3.	Welche Vorteile hat die Verwendung von OOP in PHP?
OOP (Objektorientierte Programmierung) bietet u. a.:
a.	Strukturierterer Code: Klassen logisch organisiert
b.	Wiederverwendbarkeit: durch Vererbung und Methoden
c.	Wartbarkeit: Änderungen an einer Stelle wirken überall
d.	Kapselung schützt interne Daten
e.	Modularität: Code wird in übersichtliche Einheiten zerlegt
f.	Flexibilität: durch Polymorphie (gleiches Interface, unterschiedliche Implementierung)

4.	Welche Datenkapselungsmethoden gibt es in PHP?
Datenkapselung erfolgt über Sichtbarkeiten:
a.	Public: überall zugreifbar
b.	Protected:  nur innerhalb der Klasse und ihrer Kindklassen
c.	Private: nur innerhalb der eigenen Klasse zugreifbar

5.	Wie sehen abstrakte Klassen in PHP aus?
a.	Eine abstrakte Klasse kann nicht direkt erzeugt werden.
Sie dient als Basis für andere Klassen und kann abstrakte Methoden enthalten, die von den Kindklassen implementiert werden müssen.
