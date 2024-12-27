var index
function sort_int(p1, p2) { return p1[index] - p2[index]; }			//fonction pour trier les nombres
function sort_char(p1, p2) { return ((p1[index] >= p2[index]) << 1) - 1; }	//fonction pour trier les strings

function sortTable(n, firstRow, isNum, isLink, ordDesc) {
    var FntSort = new Array()
    oTable = document.getElementById("liste");
    index = n;

    //---- Copier Tableau Html dans Table JavaScript ----//
    var Table = new Array()
    for (r = firstRow; r < oTable.rows.length; r++) Table[r - firstRow] = new Array()
    let nbCells = oTable.rows[firstRow].cells.length;

    for (c = 0; c < nbCells; c++)	//Sur toutes les cellules
    {
        objet = oTable.rows[firstRow].cells[c].innerHTML.replace(/<\/?[^>]+>/gi, "")
        if (isNum) { FntSort[c] = sort_int; } //nombre, numéraire
        else { FntSort[c] = sort_char; } //Chaine de caractère

        for (r = firstRow; r < oTable.rows.length; r++)		//De toutes les rangées
        {
            objet = oTable.rows[r].cells[c].innerHTML.replace(/<\/?[^>]+>/gi, "")
            if (isNum) {
                Table[r - firstRow][c] = Number(objet.split('&nbsp;')[0]);
            } else {
                Table[r - firstRow][c] = objet.toLowerCase();
            }
            Table[r - firstRow][c + nbCells] = oTable.rows[r].cells[c].innerHTML
        }
    }

    //--- Tri Table ---//
    Table.sort(FntSort[index]);
    if (ordDesc) Table.reverse();

    //---- Copier Table JavaScript dans Tableau Html ----//
    for (c = 0; c < nbCells; c++)	//Sur toutes les cellules
        for (r = firstRow; r < oTable.rows.length; r++)		//De toutes les rangées 
            oTable.rows[r].cells[c].innerHTML = Table[r - firstRow][c + nbCells];
}

function sortTable2(n, firstRow, isNum, isLink, ordDesc) {
    var table = document.getElementById("liste");
    var nbRows = table.rows.length;
    var x, y;

    for (i = firstRow; i < nbRows; i++) {
        x = table.rows[i].getElementsByTagName("TD")[n];
        rx = table.rows[i].innerHTML;
        if (isLink) { x = x.getElementsByTagName("A")[0]; }
        x = x.innerHTML;
        if (isNum) { x = Number(x.split('&nbsp;')[0]); } else { x = x.toLowerCase(); }

        let j = i;
        let completed = false;
        while (!completed) {
            completed = true;
            if (j > firstRow) {
                y = table.rows[j - 1].getElementsByTagName("TD")[n];
                if (isLink) { y = y.getElementsByTagName("A")[0]; }
                y = y.innerHTML;
                if (isNum) { y = Number(y.split('&nbsp;')[0]); } else { y = y.toLowerCase(); }
                if ((!ordDesc && (y > x)) || (ordDesc && (y < x))) {
                    completed = false;
                    table.rows[j].innerHTML = table.rows[j - 1].innerHTML;
                    j = j - 1
                }
            }
        }

        table.rows[j].innerHTML = rx;
    }
}


function sortTable1(n, firstRow, isNum, isLink) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("liste");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = firstRow; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            if (isLink) {
                x = x.getElementsByTagName("A")[0];
                y = y.getElementsByTagName("A")[0];
            }

            x = x.innerHTML;
            y = y.innerHTML;

            if (isNum) {
                x = Number(x.split('&nbsp;')[0]);
                y = Number(y.split('&nbsp;')[0]);

            } else {
                x = x.toLowerCase();
                y = y.toLowerCase();
            }
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x > y) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x < y) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
    console.log("Terminé");
}