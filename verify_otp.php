<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GM Khata Tool</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
body{
font-family: Arial;
margin:0;
background:#f4f4f4;
transition:0.3s;
}
.dark{
background:#1e1e1e;
color:white;
}
header{
background:#2c3e50;
color:white;
padding:15px;
text-align:center;
}
.container{
padding:15px;
}
input,select,button{
padding:8px;
margin:5px;
}
.card{
background:white;
padding:10px;
margin:8px 0;
border-radius:8px;
box-shadow:0 2px 6px rgba(0,0,0,0.2);
}
.dark .card{
background:#2c2c2c;
}
.green{color:green;}
.red{color:red;}
.flex{display:flex;flex-wrap:wrap;}
.flex input{flex:1;}
.dashboard{
display:flex;
flex-wrap:wrap;
gap:10px;
}
.dashboard div{
background:#3498db;
color:white;
padding:10px;
border-radius:8px;
flex:1;
text-align:center;
}
@media(max-width:600px){
.dashboard{flex-direction:column;}
}
</style>
</head>
<body>

<header>
<h2>GM Khata Tool</h2>
<button onclick="toggleMode()">Dark / Light</button>
<button onclick="printPage()">Print</button>
<button onclick="downloadPDF()">Download PDF</button>
<button onclick="screenshot()">Screenshot</button>
<button onclick="clearData()">Clear Data</button>
</header>

<div class="container">

<h3>Add Entry</h3>
<div class="flex">
<input id="name" placeholder="Customer Name">
<input id="phone" placeholder="Phone">
<input id="address" placeholder="Address">
</div>
<input id="note" placeholder="Note / Description">
<input type="number" id="amount" placeholder="Amount">
<select id="type">
<option value="debit">Udhar Diya (Debit)</option>
<option value="credit">Paise Liye (Credit)</option>
</select>
<button onclick="addEntry()">Add</button>

<h3>Search / Filter</h3>
<input id="search" placeholder="Search by Name" onkeyup="render()">
<input type="date" id="dateFilter" onchange="render()">

<h3>Dashboard</h3>
<div class="dashboard">
<div>Total Debit<br><span id="totalDebit">0</span></div>
<div>Total Credit<br><span id="totalCredit">0</span></div>
<div>Final Balance<br><span id="balance">0</span></div>
</div>

<h3>Entries</h3>
<div id="entries"></div>

<h3>Profit Calculator</h3>
<input type="number" id="cost" placeholder="Cost Price">
<input type="number" id="sell" placeholder="Sell Price">
<button onclick="calcProfit()">Calculate</button>
<div id="profitResult"></div>

<h3>Installment Tracker</h3>
<input type="number" id="totalInstall" placeholder="Total Amount">
<input type="number" id="paidInstall" placeholder="Paid Amount">
<button onclick="calcInstall()">Check</button>
<div id="installResult"></div>

<h3>Due Reminder</h3>
<button onclick="sendWhatsApp()">Generate WhatsApp Reminder</button>

</div>

<script>
let data = JSON.parse(localStorage.getItem("khata")) || [];

function save(){
localStorage.setItem("khata",JSON.stringify(data));
}

function addEntry(){
let name=document.getElementById("name").value;
let phone=document.getElementById("phone").value;
let address=document.getElementById("address").value;
let note=document.getElementById("note").value;
let amount=parseFloat(document.getElementById("amount").value);
let type=document.getElementById("type").value;
let date=new Date().toISOString().split("T")[0];

if(!name || !amount) return alert("Name and Amount required");

data.push({name,phone,address,note,amount,type,date});
save();
render();
}

function render(){
let search=document.getElementById("search").value.toLowerCase();
let dateFilter=document.getElementById("dateFilter").value;

let container=document.getElementById("entries");
container.innerHTML="";
let totalDebit=0,totalCredit=0;

data.forEach((item,i)=>{
if(item.name.toLowerCase().includes(search) &&
(!dateFilter || item.date==dateFilter)){
let div=document.createElement("div");
div.className="card";
div.innerHTML=`
<b>${item.name}</b> (${item.date})<br>
${item.note}<br>
${item.type=="debit"?"Debit":"Credit"}: ${item.amount}
`;
container.appendChild(div);

if(item.type=="debit") totalDebit+=item.amount;
else totalCredit+=item.amount;
}
});

let balance=totalDebit-totalCredit;

document.getElementById("totalDebit").innerText=totalDebit;
document.getElementById("totalCredit").innerText=totalCredit;
document.getElementById("balance").innerText=balance;

document.getElementById("balance").className=balance>=0?"red":"green";
}

function clearData(){
if(confirm("Clear all data?")){
data=[];
save();
render();
}
}

function toggleMode(){
document.body.classList.toggle("dark");
}

function printPage(){
window.print();
}

async function downloadPDF(){
const { jsPDF } = window.jspdf;
let doc=new jsPDF();
doc.text("GM Khata Report",10,10);
doc.text("Total Debit: "+document.getElementById("totalDebit").innerText,10,20);
doc.text("Total Credit: "+document.getElementById("totalCredit").innerText,10,30);
doc.text("Balance: "+document.getElementById("balance").innerText,10,40);
doc.save("khata.pdf");
}

function screenshot(){
html2canvas(document.body).then(canvas=>{
let link=document.createElement("a");
link.download="screenshot.png";
link.href=canvas.toDataURL();
link.click();
});
}

function calcProfit(){
let cost=parseFloat(document.getElementById("cost").value);
let sell=parseFloat(document.getElementById("sell").value);
let profit=sell-cost;
document.getElementById("profitResult").innerText="Profit: "+profit;
}

function calcInstall(){
let total=parseFloat(document.getElementById("totalInstall").value);
let paid=parseFloat(document.getElementById("paidInstall").value);
let due=total-paid;
document.getElementById("installResult").innerText="Remaining: "+due;
}

function sendWhatsApp(){
let balance=document.getElementById("balance").innerText;
let msg="Assalamualaikum, aap ka due balance hai: "+balance;
window.open("https://wa.me/?text="+encodeURIComponent(msg));
}

render();
</script>

</body>
</html>
