<?php
// เชื่อมต่อ DB
$host = "mariadb";
$user = "warusnee";
$pass = "4412";
$db   = "Warusnee";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "DB CONNECTED";
if(isset($_POST['add'])){
    $value = $conn->real_escape_string($_POST['value']);
    $conn->query("INSERT INTO trees (name) VALUES ('$value')");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
// CREATE TABLE
$sql = "CREATE TABLE IF NOT EXISTS trees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    height INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal BST CRUD</title>
    <style>
        /* CSS: เน้นความมินิมอลและโทนสีที่ทันสมัย */
        :root {
            --bg: #f5f6fa;
            --card-bg: #ffffff;
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --accent: #00b894;
            --text: #2d3436;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .header {
            margin-top: 40px;
            text-align: center;
        }

        .container {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin: 20px;
            width: 90%;
            max-width: 800px;
        }

        .controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        input {
            padding: 12px 18px;
            border: 2px solid #edf2f7;
            border-radius: 12px;
            outline: none;
            transition: 0.3s;
            width: 120px;
        }

        input:focus { border-color: var(--primary); }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-add { background: var(--primary); color: white; }
        .btn-search { background: var(--secondary); color: white; }
        .btn-clear { background: #dfe6e9; color: var(--text); }
        button:hover { opacity: 0.9; transform: translateY(-2px); }

        #tree-canvas {
            width: 100%;
            height: 400px;
            border-top: 1px solid #f1f2f6;
            margin-top: 20px;
        }

        .node-circle {
            fill: white;
            stroke: var(--primary);
            stroke-width: 3;
        }

        .node-text {
            font-size: 14px;
            font-weight: bold;
            text-anchor: middle;
            fill: var(--text);
        }

        .edge-line {
            stroke: #dfe6e9;
            stroke-width: 2;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>🌳 Binary Search Tree</h1>
        <p>Simple • Minimal • Functional</p>
    </div>

    <div class="container">
        <div class="controls">
            <input type="number" id="nodeInput" placeholder="ใส่ตัวเลข...">
            <button class="btn-add" onclick="insertNode()">Add</button>
            <button class="btn-search" onclick="searchNode()">Search</button>
            <button class="btn-clear" onclick="resetTree()">Clear</button>
        </div>
        
        <svg id="tree-canvas"></svg>
    </div>

    <script>
        // JS: Logic ของ Binary Search Tree
        class Node {
            constructor(value) {
                this.value = value;
                this.left = null;
                this.right = null;
            }
        }

        let root = null;

        function insertNode() {
            const val = parseInt(document.getElementById('nodeInput').value);
            if (isNaN(val)) return;

            const newNode = new Node(val);
            if (!root) root = newNode;
            else addNode(root, newNode);

            document.getElementById('nodeInput').value = '';
            drawTree();
        }

        function addNode(node, newNode) {
            if (newNode.value < node.value) {
                if (!node.left) node.left = newNode;
                else addNode(node.left, newNode);
            } else {
                if (!node.right) node.right = newNode;
                else addNode(node.right, newNode);
            }
        }

        function searchNode() {
            const val = parseInt(document.getElementById('nodeInput').value);
            if (isNaN(val)) return;
            alert(find(root, val) ? ✅ เจอค่า ${val} ในระบบ! : ❌ ไม่พบค่า ${val});
        }

        function find(node, val) {
            if (!node) return false;
            if (node.value === val) return true;
            return val < node.value ? find(node.left, val) : find(node.right, val);
        }

        function resetTree() {
            root = null;
            drawTree();
        }

        // ส่วนของการวาดรูป (Visualization)
        const svg = document.getElementById('tree-canvas');

        function drawTree() {
            svg.innerHTML = '';
            if (root) render(root, 400, 50, 200);
        }

        function render(node, x, y, spacing) {
            if (node.left) {
                drawLine(x, y, x - spacing, y + 60);
                render(node.left, x - spacing, y + 60, spacing / 2);
            }
            if (node.right) {
                drawLine(x, y, x + spacing, y + 60);
                render(node.right, x + spacing, y + 60, spacing / 2);
            }
            drawCircle(x, y, node.value);
        }

        function drawLine(x1, y1, x2, y2) {
            const line = <line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" class="edge-line" />;
            svg.innerHTML += line;
        }

        function drawCircle(x, y, val) {
            const group = `
                <g>
                    <circle cx="${x}" cy="${y}" r="20" class="node-circle" />
                    <text x="${x}" y="${y + 5}" class="node-text">${val}</text>
                </g>
            `;
            svg.innerHTML += group;
        }
    </script>
</body>

</html>





