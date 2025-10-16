// Funktion: zeigt die gewählte Section
function showSection(sectionId) {
    const sections = document.querySelectorAll('section');
    sections.forEach(sec => sec.classList.remove('active'));
    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');
}

// ------------------------------------------------------
// Erweiterte Funktion: Wasserbedarf mit echten API-Daten
// ------------------------------------------------------
async function calculateWater(event) {
    event.preventDefault();
    const topf = document.getElementById('topf').value;
    const zeitraum = parseInt(document.getElementById('zeitraum').value);

    // 🔹 Hole Daten aus deiner eigenen Datenbank-API
    const response = await fetch(`/php/api.php?days=${zeitraum}`);
    const data = await response.json();

    // Durchschnitt berechnen
    let avgUV = 0;
    let avgTemp = 0;
    if (data.length > 0) {

        const tagesdaten = new Map();
        data.forEach(eintrag => {
            let eintragsdatum = new Date(eintrag.timestamp);
            eintragsdatum = eintragsdatum.toISOString().split('T')[0]; // Nur das Datum (YYYY-MM-DD)
            if (!tagesdaten.has(eintragsdatum)) {
                tagesdaten.set(eintragsdatum, { uv: [], temp: [] });
            }
            if(eintrag.uv_index !== null && eintrag.temperature !== null) {
                tagesdaten.get(eintragsdatum).uv.push(eintrag.uv_index);
                tagesdaten.get(eintragsdatum).temp.push(eintrag.temperature);
            }

        });

        const uvValues = [];
        const tempValues = [];

        tagesdaten.forEach((werte, datum) => {

            let tagesDurchschnittUV = 0;
            let tagesDurchschnittTemp = 0;

            werte.uv.forEach(v => {
                tagesDurchschnittUV += v;
            });
            werte.temp.forEach(v => {
               tagesDurchschnittTemp += v;
            });

            tagesDurchschnittUV /= werte.uv.length;
            tagesDurchschnittTemp /= werte.temp.length;
            
            uvValues.push(tagesDurchschnittUV);
            tempValues.push(tagesDurchschnittTemp);
            
        });

        // Durchschnitt über alle Tage (Durchschnitt von uvValues und tempValues)
        avgUV = uvValues.reduce((a, b) => a + b, 0) / uvValues.length;
        avgTemp = tempValues.reduce((a, b) => a + b, 0) / tempValues.length;
    }

    let wasser = 0;
    if(topf === 'small') wasser = 150;
    if(topf === 'medium') wasser = 250;
    if(topf === 'large') wasser = 400;

    wasser *= zeitraum / 7;

    // ----------------- Nur dieser Teil minimal angepasst -----------------
    document.getElementById('ergebnisUV').innerText =
        `In den letzten ${zeitraum} Tagen war der durchschnittliche UV-Index ${avgUV.toFixed(1)} ` +
        `und die Temperatur ${avgTemp.toFixed(1)}°C.`;

    document.getElementById('ergebnisWasser').innerText =
        `Deshalb solltest du deine Pflanze ${Math.round(wasser)} ml Wasser geben.`;
    // ----------------------------------------------------------------------

    showSection('ergebnis');
}
