<?php 
include "sb/sblog.php";
sblog_header();
?>

<div class="all">


    <div class="header">
    
        <div class="titlebar">
            <?php sb_titlebar(); ?>
        </div><!-- .titlebar -->
        
        <div class="main-menu">
            <?php sb_menu(); ?>
        </div><!-- .main-menu -->
    
    </div><!-- .header -->


    <div class="contents">
        
        <?php sblog(); ?>
        
        <div class="sidebar">
            
            <div class="sidebar-block">
                <h3>Sobre o Sblog</h3>
                <p>O Sblog é um pequeno script experimental em PHP para transformar notas publicadas pelo Simplenote em um blog.</p>
                <p>Trata-se de uma prova de conceito, basicamente para fins de aprendizado, mas é funcional o bastante para a criação de pequenos sites pessoais.</p>
            </div>
            
            <div class="sidebar-block">
                <h3>Atenção!</h3>
                <p>O Sblog é totalmente dependente da disponibilidade das notas compartilhadas do Simplenote. Caso o serviço seja descontinuado ou sofra alterações, todo o blog deixará de funcionar.</p>
            </div>
            
        </div><!-- .sidebar -->
    
    </div><!-- .contents -->


    <div class="footer">
        <p><a href="http://debxp.org/sblog/">Sblog</a> is powered by <a href="http://simplenote.com">Simplenote</a> and PHP</p>
    </div><!-- .footer -->


</div><!-- .all -->


<?php sblog_footer(); ?>
