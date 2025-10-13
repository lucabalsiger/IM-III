// Funktion: zeigt die gewählte Section
function showSection(sectionId) {
    const sections = document.querySelectorAll('section');
    sections.forEach(sec => sec.classList.remove('active'));
    const target = document.getElementById(sectionId);
    if(target) target.classList.add('active');
}

// Platzhalter-Berechnung Wasserbedarf
function calculateWater(event) {
    event.preventDefault();
    const topf = document.getElementById('topf').value;
    const zeitraum = parseInt(document.getElementById('zeitraum').value);

    const uvIndex = 5;
    const temp = 22;
    let wasser = 0;

    if(topf === 'small') wasser = 150;
    if(topf === 'medium') wasser = 250;
    if(topf === 'large') wasser = 400;

    wasser *= zeitraum / 7;

    document.getElementById('ergebnisText').innerText =
        `In den letzten ${zeitraum} Tagen war der UV-Index ${uvIndex} und die Temperatur ${temp}°C. ` +
        `Deshalb solltest du deine Pflanze ${Math.round(wasser)} ml Wasser geben.`;

    showSection('ergebnis');
}
