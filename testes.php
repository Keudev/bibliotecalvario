<?php
$mysqli = new SQLite3('../../data/bibliotecario.db');

// Filtros
$filterTitulo = $_GET['filterTitulo'] ?? '';
$filterCategoria = $_GET['filterCategoria'] ?? '';
$filterLocal = $_GET['filterLocal'] ?? '';

// Paginação
$recordsPerPage = 2;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Total de registros
$totalQuery = "
    SELECT COUNT(*) as count 
    FROM cad_acervo a
    LEFT JOIN cad_categoria c ON a.categoria = c.id
    LEFT JOIN cad_tipo t ON a.tipo = t.id
    WHERE a.titulo LIKE '%$filterTitulo%' 
    AND c.titulo LIKE '%$filterCategoria%' 
    AND (a.setor LIKE '%$filterLocal%' OR a.prateleira LIKE '%$filterLocal%' OR a.estante LIKE '%$filterLocal%')
";
$totalResult = $mysqli->query($totalQuery);
$totalRow = $totalResult->fetchArray(SQLITE3_ASSOC);
$totalRecords = $totalRow['count'];
$totalPages = ceil($totalRecords / $recordsPerPage);

$query = "
SELECT a.id, a.titulo, a.autor, a.editora, c.titulo AS categoria, t.descricao AS tipo, a.quantidade, a.prateleira, a.estante, a.setor
FROM cad_acervo a
LEFT JOIN cad_categoria c ON a.categoria = c.id
LEFT JOIN cad_tipo t ON a.tipo = t.id
LIMIT $recordsPerPage OFFSET $offset
";
$result = $mysqli->query($query);
?>
<style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            white-space: nowrap; /* Evita quebra de linha no texto das células */
        }
        th {
            background-color: #f2f2f2;
            color: black;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        /* Estilo para tornar a coluna "Nome" fixa */
        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            background-color: #f2f2f2;
            z-index: 1;
        }
        .sticky-col th, .sticky-col td {
            background-color: #f2f2f2;
        }
        /* Ocultar a coluna "Ações" durante a impressão */
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
            body * {
                visibility: hidden;
            }
            .printableTable, .printableTable * {
                visibility: visible;
            }
            .printableTable {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    /* Estilo para paginação */
    .pagination {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }
    .pagination a {
        margin: 0 5px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        color: #007bff;
        text-decoration: none;
    }
    .pagination a.active {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }
    .pagination a:hover {
        background-color: #ddd;
    }
        
    </style>

<br><br>
    <h2>Lista de Acervos</h2>
    <div class="row">
        <div class="col-lg-3"> 
            <div class="form-group">
                <label for="filterNome">Filtrar por Título:</label>
                <input type="text" id="filterTitulo" style="border: 1px solid #C0C0C0;" class="form-control" placeholder="Digite um título">
            </div>
        </div>
       
        <div class="col-lg-3"> 
            <div class="form-group">
                <label for="filterTurma">Filtrar por Categoria:</label>
                <input type="text" style="border: 1px solid #C0C0C0;" id="filterCategoria" class="form-control" placeholder="Digite uma categoria">
            </div>
        </div>
        <div class="col-lg-3"> 
            <div class="form-group">
                <label for="filterTurma">Filtrar por Local:</label>
                <input type="text" style="border: 1px solid #C0C0C0;" id="filterLocal" class="form-control" placeholder="Digite localização">
            </div>
        </div>
        <div class="col-lg-3"> 
           <br> <br>
            <div class="row">
                <div class="col-lg-12"> 
                    <!--
                    <button type="button"  class="btn btn-primary btn-icon-text exportar" onclick="exportTableToExcel('usuariosTable', 'usuarios-sistema-bibliotecario')">
                        <i class="mdi mdi-format-vertical-align-bottom btn-icon-prepend"></i>
                        Exportar
                    </button>
                    -->
                
                    <button type="button" class="btn btn-warning btn-icon-text gerar_carteira">
                        <i class="mdi mdi-account-card-details btn-icon-prepend"></i>
                        Gerar Etiqueta
                    </button> 
                </div>
                
            </div>
        </div>
    </div>
    <div class="table-responsive printableTable">
        <table id="usuariosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="sticky-col">Titulo</th>
                    <th>Categoria</th>
                    <th>Local (S/E/P)</th>
                                       
                    <th class='text-center no-print'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
               

                // Verifica se há registros e os exibe
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $id = htmlspecialchars($row['id']);
                    $titulo = htmlspecialchars($row['titulo']);
                    $autor = htmlspecialchars($row['autor']);
                    $prateleira = htmlspecialchars($row['prateleira']);
                    $estante = htmlspecialchars($row['estante']);
                    $editora = htmlspecialchars($row['editora']);
                    $categoria = htmlspecialchars($row['categoria']);
                    $tipo = htmlspecialchars($row['tipo']);
                    $setor = htmlspecialchars($row['setor']);
                    $quantidade = htmlspecialchars($row['quantidade']);

                    
                    echo "<tr>";
                    echo "<td>$id</td>";
                    echo "<td>$titulo</td>";
                    //echo "<td>$autor</td>";
                    //echo "<td>$editora</td>";
                    echo "<td>$categoria</td>";
                    echo "<td>$setor/$estante/$prateleira</td>";
                    
                    
                    //echo "<td>$tipo</td>";
                   // echo "<td>$quantidade</td>";
                    echo "<td class='action-buttons text-center no-print'>
                            <button type='button' class='btn btn-warning btn-rounded btn-icon edit-btn' data-id='$id'><i class='mdi mdi-pencil'></i></button>
                            <button type='button' class='btn btn-danger btn-rounded btn-icon delete-btn' data-id='$id'><i class='mdi mdi-delete-forever'></i></button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'active' : '';
                echo "<a class='page-link $active' href='javascript:void(0)' data-page='$i'>$i</a>";
            }
        }
        ?>
    </div>
    

    <script>
        $(document).ready(function() {
            $(".gerar_carteira").click(function(){
                $('.painel_tabela').load("cad_acervo/gerar_etiqueta.php")
            })
            // Função para editar um registro
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                $('.painel_tabela').load("cad_acervo/editar_acervo.php?id="+id)
            });

            // Função para excluir um registro
            $('.delete-btn').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, deletar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'cad_acervo/delete_acervo.php',
                            data: { id: id },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status === 'success') {
                                    Swal.fire(
                                        'Deletado!',
                                        res.message,
                                        'success'
                                    ).then(() => {
                                        $('.painel_tabela').load("cad_acervo/tabela.php")
                                    });
                                } else {
                                    Swal.fire(
                                        'Erro!',
                                        res.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Erro!',
                                    'Erro ao deletar o registro.',
                                    'error'
                                );
                                console.error(xhr);
                            }
                        });
                    }
                });
            });

            $('.page-link').on('click', function() {
                var page = $(this).data('page');
                loadTable(page);
            });
            function loadTable(page = 1) {
                    var filterTitulo = $('#filterTitulo').val().trim();
                    var filterCategoria = $('#filterCategoria').val().trim();
                    var filterLocal = $('#filterLocal').val().trim();

                    $.ajax({
                        url: 'cad_acervo/tabela.php',
                        type: 'GET',
                        data: {
                            page: page,
                            filterTitulo: filterTitulo,
                            filterCategoria: filterCategoria,
                            filterLocal: filterLocal
                        },
                        success: function(response) {
                            $('.painel_tabela').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                        }
                    });
                }

            // Funções de filtro
            $('#filterTitulo, #filterCategoria, #filterLocal').on('input change', function() {
                var filterTitulo = $('#filterTitulo').val().toLowerCase();
                var filterCategoria = $('#filterCategoria').val().toLowerCase();
                var filterLocal = $('#filterLocal').val().toLowerCase();

                $('#usuariosTable tbody tr').filter(function() {
                    $(this).toggle(
                        ($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(filterTitulo) > -1 || filterTitulo === '') &&
                        ($(this).find('td:nth-child(3)').text().toLowerCase().indexOf(filterCategoria) > -1 || filterCategoria === '')  &&
                        ($(this).find('td:nth-child(4)').text().toLowerCase().indexOf(filterLocal) > -1 || filterLocal === '')
                        
                    );
                });
            });

           

           
        })    

        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            var tableSelect = document.getElementById(tableID);
            var workbook = XLSX.utils.table_to_book(tableSelect, {sheet:"Sheet1"});
            var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

            // Criação de um Blob com os dados em formato Excel
            var data = new Blob([excelBuffer], {type: dataType});
            var csvURL = window.URL.createObjectURL(data);

            // Criação do link de download
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                navigator.msSaveOrOpenBlob(data, filename + '.xlsx');
            } else {
                // Link para o download
                downloadLink.href = csvURL;
                downloadLink.setAttribute('download', filename + '.xlsx');
                downloadLink.click();
            }
        }

    </script>

