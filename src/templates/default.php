<!DOCTYPE html>
<HTML>
    <HEAD>
        <title><?= $boleto->getCedente()->getNome() ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="pt-br"/>
        <style type="text/css">
        @media print {
            .noprint {
                display:none;
            }
        }

        body{
            color: #000000;
            background-color: #ffffff;
            margin-top: 0;
            margin-right: 0;
        }

        .table-boleto {
            font: 9px Arial;
            width: 666px;
        }

        .table-boleto td {
            border-left: 1px solid #000;
            border-top: 1px solid #000;
        }

        .table-boleto td:last-child {
            border-right: 1px solid #000;
        }

        .table-boleto .titulo {
            color: #000000;
        }

        .linha-pontilhada {
            color: #000000;
            font: 9px Arial;
            width: 100%;
            border-bottom: 1px dashed #000;
            text-align: right;
            margin-bottom: 10px;
        }

        .table-boleto .conteudo {
            font: bold 10px Arial;
            min-height: 13px;
        }

        .table-boleto .sacador {
            display: inline;
            margin-left: 5px;
        }

        .table-boleto td {
            padding: 1px 4px;
        }

        .table-boleto .noleftborder {
            border-left: none !important;
        }

        .table-boleto .notopborder {
            border-top: none !important;
        }

        .table-boleto .norightborder {
            border-right: none !important;
        }

        .table-boleto .noborder {
            border: none !important;
        }

        .table-boleto .bottomborder {
            border-bottom: 1px solid #000 !important;
        }

        .table-boleto .rtl {
            text-align: right;
        }

        .table-boleto .logobanco {
            display: inline-block;
            max-width: 150px;
        }

        .table-boleto .logocontainer {
            width: 257px;
            display: inline-block;
        }

        .table-boleto .logobanco img {
            margin-bottom: -5px;
        }

        .table-boleto .codbanco {
            font: bold 20px Arial;
            padding: 1px 5px;
            display: inline;
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            width: 51px;
            margin-left: 25px;
        }

        .table-boleto .linha-digitavel {
            font: bold 14px Arial;
            display: inline-block;
            width: 406px;
            text-align: right;
        }

        .table-boleto .nopadding {
            padding: 0px !important;
        }

        .table-boleto tr td {
            position: relative;
        }

        .info {
            font: 11px Arial;
        }

        .info-empresa {
            font: 11px Arial;
        }

        .header {
            font: bold 13px Arial;
            display: block;
            margin: 4px;
        }

        .barcode {
            height: 50px;
            margin: 10px 0 0 0;
        }

        .barcode div {
            display: inline-block;
            height: 100%;
        }

        .barcode .black {
            border-color: #000;
            border-left-style: solid;
            width: 0px;
        }

        .barcode .white {
            background: #fff;
        }

        .barcode .thin.black {
            border-left-width: 1px;
        }

        .barcode .large.black {
            border-left-width: 3px;
        }

        .barcode .thin.white {
            width: 1px;
        }

        .barcode .large.white {
            width: 3px;
        }
        </style>
    </head>
    <body>
        <div class="noprint info" style="width: 666px">
            <h2>Instruções de Impressão</h2>
            <ul>
                <li>
                    Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Não use modo econômico).
                </li>
                <li>
                    Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens mínimas à esquerda e à direita do formulário.
                </li>
                <li>
                    Corte na linha indicada. Não rasure, risque, fure ou dobre a região onde se encontra o código de barras.
                </li>
                <li>
                    Caso não apareça o código de barras no final, pressione F5 para atualizar esta tela.
                </li>
                <li>
                    Caso tenha problemas ao imprimir, copie a sequencia numérica abaixo e pague no caixa eletrônico ou no internet banking:
                </li>
            </ul>
            <span class="header">Linha Digitável: <?= $boleto->getLinhaDigitavel() ?></span>
            <span class="header">Valor: R$ <?= $boleto->getValorBoleto(true) ?></span><br />
            <div class="linha-pontilhada" style="margin-bottom: 20px;">Recibo do sacado</div>
        </div>
        <div class="info-empresa">
            <div style="display: inline-block;">
                <img src="data:image/png;base64, <?= $boleto->getCedente()->getLogo() ?>" />
            </div>
            <div style="display: inline-block; vertical-align: super; margin-left: 10px;">
                <div style="font: 14px Arial;"><strong><?= $boleto->getCedente()->getNome() ?></strong></div>
                <div><?= $boleto->getCedente()->getEndereco() ?></div>
                <div><?= $boleto->getCedente()->getCidadeUf() ?></div>
            </div>
        </div>
        <br />
        <table class="table-boleto" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td valign="bottom" colspan="8" class="noborder nopadding">
                    <div class="logocontainer">
                        <div class="logobanco">
                            <img src="data:image/jpg;base64, <?= $boleto->getLogoBanco() ?>" border=0 />
                        </div>
                        <div class="codbanco"><?= $boleto->getCodigoBancoDv() ?></div>
                    </div>
                    <div class="linha-digitavel"><?= $boleto->getLinhaDigitavel() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="250">
                    <div class="titulo">Cedente</div>
                    <div class="conteudo"><?= $boleto->getCedente()->getNome() ?></div>
                </td>
                <td>
                    <div class="titulo">CPF/CNPJ</div>
                    <div class="conteudo"><?= $boleto->getCedente()->getCpfCnpj() ?></div>
                </td>
                <td width="120">
                    <div class="titulo">Agência/Código do Cedente</div>
                    <div class="conteudo rtl"><?= $boleto->getAgenciaCodigoBoleto() ?></div>
                </td>
                <td width="110">
                    <div class="titulo">Vencimento</div>
                    <div class="conteudo rtl"><?= $boleto->getDataVencimento()->format('d/m/Y') ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="titulo">Sacado</div>
                    <div class="conteudo"><?= $boleto->getSacado()->getNome() ?></div>
                </td>
                <td>
                    <div class="titulo">Nº documento</div>
                    <div class="conteudo rtl"><?= $boleto->getNumeroDocumento() ?></div>
                </td>
                <td>
                    <div class="titulo">Nosso número</div>
                    <div class="conteudo rtl"><?= $boleto->getNossoNumeroBoleto() ?></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="titulo">Espécie</div>
                    <div class="conteudo"><span class="campo"><?= $boleto->getEspecie() ?></span></div>
                </td>
                <td>
                    <div class="titulo">Quantidade</div>
                    <div class="conteudo rtl"><?= $boleto->getQuantidade() ?></div>
                </td>
                <td>
                    <div class="titulo">Valor</div>
                    <div class="conteudo rtl"><?= $boleto->getValorUnitario() ?></div>
                </td>
                <td>
                    <div class="titulo">(-) Descontos / Abatimentos</div>
                    <div class="conteudo rtl"><?= $boleto->getDesconto() ?></div>
                </td>
                <td>
                    <div class="titulo">(=) Valor Documento</div>
                    <div class="conteudo rtl"><?= $boleto->getValorBoleto(true) ?> <!-- valor_documento --></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="conteudo"></div>
                    <div class="titulo">Demonstrativo</div>
                </td>
                <td>
                    <div class="titulo">(-) Outras deduções</div>
                    <div class="conteudo"><?= $boleto->getDeducao() ?></div>
                </td>
                <td>
                    <div class="titulo">(+) Outros acréscimos</div>
                    <div class="conteudo rtl"><?= $boleto->getAcrescimo() ?></div>
                </td>
                <td>
                    <div class="titulo">(=) Valor cobrado</div>
                    <div class="conteudo rtl"><?= $boleto->getValorBoleto(true) ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="4"><div style="margin-top: 10px" class="conteudo"><?= $boleto->getObservacao1() ?></div></td>
                <td class="noleftborder"><div class="titulo">Autenticação mecânica</div></td>
            </tr>
            <tr>
                <td colspan="5" class="notopborder"><div class="conteudo"><?= $boleto->getObservacao2() ?></div></td>
            </tr>
            <tr>
                <td colspan="5" class="notopborder"><div class="conteudo"><?= $boleto->getObservacao3() ?></div></td>
            </tr>
            <tr>
                <td colspan="5" class="notopborder bottomborder"><div style="margin-bottom: 10px;" class="conteudo"><?= $boleto->getObservacao4() ?></div></td>
            </tr>
            <tr>
                <td colspan="5" class="noborder"></td>
            </tr>
        </table>

        <br>
        <div style="width: 666px" class="linha-pontilhada">Corte na linha pontilhada</div>
        <br>

        <table class="table-boleto" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td valign="bottom" colspan="8" class="noborder nopadding">
                    <div class="logocontainer">
                        <div class="logobanco">
                            <img src="data:image/jpg;base64, <?= $boleto->getLogoBanco() ?>" border=0>
                        </div>
                        <div class="codbanco"><?= $boleto->getCodigoBancoDv() ?></div>
                    </div>
                    <div class="linha-digitavel"><?= $boleto->getLinhaDigitavel() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="titulo">Local de pagamento</div>
                    <div class="conteudo">Pagável em qualquer Banco até o vencimento</div>
                </td>
                <td width="180">
                    <div class="titulo">Vencimento</div>
                    <div class="conteudo rtl"><?= $boleto->getDataVencimento()->format('d/m/Y') ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="titulo">Cedente</div>
                    <div class="conteudo"><?= $boleto->getCedente()->getNome() ?></div>
                </td>
                <td>
                    <div class="titulo">Agência/Código cedente</div>
                    <div class="conteudo rtl"><?= $boleto->getAgenciaCodigoBoleto() ?></div>
                </td>
            </tr>
            <tr>
                <td width="110" colspan="2">
                    <div class="titulo">Data do documento</div>
                    <div class="conteudo"><?= $boleto->getDataEmissao()->format('d/m/Y') ?></div>
                </td>
                <td width="120" colspan="2">
                    <div class="titulo">Nº documento</div>
                    <div class="conteudo"><?= $boleto->getNumeroDocumento() ?></div>
                </td>
                <td width="60">
                    <div class="titulo">Espécie doc.</div>
                    <div class="conteudo"><?= $boleto->getEspecieDoc() ?></div>
                </td>
                <td>
                    <div class="titulo">Aceite</div>
                    <div class="conteudo"><?= $boleto->getAceite() ?></div>
                </td>
                <td width="110">
                    <div class="titulo">Data processamento</div>
                    <div class="conteudo"><?= $boleto->getDataProcessamento()->format('d/m/Y') ?></div>
                </td>
                <td>
                    <div class="titulo">Nosso número</div>
                    <div class="conteudo rtl"><?= $boleto->getNossoNumeroBoleto() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="titulo">Uso do banco</div>
                    <div class="conteudo"></div>
                </td>
                <td>
                    <div class="titulo">Carteira</div>
                    <div class="conteudo"><?= $boleto->getCarteira() . $boleto->getVariacaoCarteira() ?></div>
                </td>
                <td width="35">
                    <div class="titulo">Espécie</div>
                    <div class="conteudo"><?= $boleto->getEspecie() ?></div>
                </td>
                <td colspan="2">
                    <div class="titulo">Quantidade</div>
                    <div class="conteudo"><?= $boleto->getQuantidade() ?></div>
                </td>
                <td width="110">
                    <div class="titulo">Valor</div>
                    <div class="conteudo"></div>
                </td>
                <td>
                    <div class="titulo">(=) Valor do Documento</div>
                    <div class="conteudo rtl"><?= $boleto->getValorBoleto(true) /* $valor_documento */ ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="titulo">Instruções</div>
                </td>
                <td>
                    <div class="titulo">(-) Descontos / Abatimentos</div>
                    <div class="conteudo rtl"><?= $boleto->getDesconto() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="notopborder">
                    <div class="conteudo"><?= $boleto->getInstrucao1() ?></div>
                    <div class="conteudo"><?= $boleto->getInstrucao2() ?></div>
                </td>
                <td>
                    <div class="titulo">(-) Outras deduções</div>
                    <div class="conteudo rtl"><?= $boleto->getDeducao() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="notopborder">
                    <div class="conteudo"><?= $boleto->getInstrucao3() ?></div>
                    <div class="conteudo"><?= $boleto->getInstrucao4() ?></div>
                </td>
                <td>
                    <div class="titulo">(+) Mora / Multa</div>
                    <div class="conteudo rtl"><?= $boleto->getMoraJuro() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="notopborder">
                    <div class="conteudo"><?= $boleto->getInstrucao5() ?></div>
                    <div class="conteudo"><?= $boleto->getInstrucao6() ?></div>
                </td>
                <td>
                    <div class="titulo">(+) Outros acréscimos</div>
                    <div class="conteudo rtl"><?= $boleto->getAcrescimo() ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="notopborder">
                    <div class="conteudo"><?= $boleto->getInstrucao7() ?></div>
                    <div class="conteudo"><?= $boleto->getInstrucao8() ?></div>
                </td>
                <td>
                    <div class="titulo">(=) Valor cobrado</div>
                    <div class="conteudo rtl"><?= $boleto->getValorCobrado() /* valor_cobrado */ ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="titulo">Sacado</div>
                    <div class="conteudo">
                        <div><?= $boleto->getSacado()->getNome() ?></div>
                        <div><?= $boleto->getSacado()->getEndereco() ?></div>
                        <div><?= $boleto->getSacado()->getEnderecoComplemento() ?></div>
                    </div>
                </td>
                <td>
                    <div class="titulo" style="position: absolute; top: 2px;">Cód. Baixa</div>
                    <div class="conteudo">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="noleftborder">
                    <div class="titulo">Sacador/Avalista <div class="conteudo sacador"><?= $boleto->getAvalista() ?></div></div>
                </td>
                <td colspan="2" class="norightborder noleftborder">
                    <div class="conteudo noborder rtl">Autenticação mecânica - Ficha de Compensação</div>
                </td>
            </tr>

            <tr>
                <td colspan="8" class="noborder">
                    <?= $boleto->generateBarCode() ?>
                </td>
            </tr>
        </table>
        <div style="width: 666px" class="linha-pontilhada">Corte na linha pontilhada</div>
    </body>
</HTML>