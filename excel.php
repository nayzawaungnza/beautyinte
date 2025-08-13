<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Myanmar PDF with jsPDF</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>

<body>

    <button onclick="generatePDF()">Generate Myanmar PDF</button>

    <script>
        // ဒီမှာ font data base64 အနေနဲ့ ထည့်ထားပါတယ် (NotoSansMyanmar-Regular.ttf)
        // ကျွန်တော်က ရိုးရိုးနမူနာအတွက် တစ်ခုထည့်ထားတာပါ။ မိမိ project အတွက် Google Fonts ကနေ .ttf ရယူပြီး
        // https://rawgit.com/sphilee/jsPDF-CustomFonts-support/master/fontconverter/fontconverter.html မှာ
        // ttf ကို base64 ဖိုင်အဖြစ်ပြောင်းပြီး ထည့်ပါ။

        const notoSansMyanmarFont = "AAEAAAASAQAABAAgR0RFRrRCsIIAAjGsAAACYkdQT1P9Hjc0AAIZxAAABOhHU1VCA...";
        // **မှတ်ချက်: ဒီမှာ font data ကို ဒီလို ထည့်ထားတာက အပြည့်အစုံ မဟုတ်ပါ။ 
        // မိမိ တိုက်ရိုက် convert လုပ်ပြီးထည့်ပါ**

        async function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Font add လုပ်နည်း (base64 ဖော်ပြထားသော font)
            // မိမိကြိုက် font name ဖြင့် register လုပ်နိုင်တယ်
            doc.addFileToVFS("NotoSansMyanmar-Regular.ttf", notoSansMyanmarFont);
            doc.addFont("NotoSansMyanmar-Regular.ttf", "NotoSansMyanmar", "normal");
            doc.setFont("NotoSansMyanmar");

            doc.setFontSize(18);
            doc.text("မင်္ဂလာပါ၊ ဒီကနေ မြန်မာစာပါ PDF ဖိုင် generate လုပ်မယ်။", 10, 20);

            doc.setFontSize(12);
            doc.text("မြန်မာစာနဲ့ jsPDF ကို အသုံးပြုပြီး PDF ဖိုင်ပြုလုပ်ခြင်း။", 10, 30);

            doc.save("MyanmarSample.pdf");
        }
    </script>

</body>

</html>