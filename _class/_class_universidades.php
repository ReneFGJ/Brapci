<?php
class universidades
{
        var $tabela = "universidade";
       
        function continentes_regioes()
            {
                $ct = array(
                    'ANT'=>'Antártida',
                    'EUR'=>'Europa Ocidental',
                    'EUO'=>'Europa Oriental',
                    'EUA'=>'Euroásia',
                    'ASI'=>'Ásia',
                    'AMN'=>'América do Norte',
                    'ANS'=>'América do Sul',
                    'AMC'=>'América Central',
                    'OCE'=>'Oceania'
                    );
                return($ct);
            }
       

        function cp()
            {
                $oce = $this->continentes_regioes();
                for ($r=0;$r< 10;$r++)
                    {
                       
                    }
                   
                   
                $cp = array();
                array_push($cp,array('$H8','id_um','',False,True));
                array_push($cp,array('$H8','um_nome',msg('nome_universidade'),False,True));
                array_push($cp,array('$H8','um_codigo','',False,True));
                array_push($cp,array('$H8','um_pais',msg('pais'),False,True));
                array_push($cp,array('$H8','um_link',msg('link'),False,True));
                array_push($cp,array('$N8','um_nota','Nota da universidade',False,True));
                array_push($cp,array('$O 1:SIM&0:NÃO','um_ativo',msg('ativo'),False,True));               
            }

        function row()
            {
                global $cdf,$cdm,$masc;
                $cdf = array('id_um','um_nome','um_codigo','um_pais','um_nota','um_ativo');
                $cdm = array('cod',msg('nome'),msg('codigo'),msg('pais'),msg('nota'),msg('ativo'));
                $masc = array('','','','D','$R','');
                return(1);               
            }       
       
        function structure()
            {
                $sql = "CREATE TABLE universidades
                    (
                    id_um serial not null,
                    um_nome char(100),
                    um_codigo char(7),
                    um_pais char (60),
                    um_link char(100),
                    um_nota double,
                    um_ativo integer,
                    )
                ";
            }
           

                       
        function updatex()
            {
                global $base;
                $c = 'um';
                $c1 = 'id_'.$c;
                $c2 = $c.'_protocolo';
                $c3 = 7;
                $sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
                if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
                $rlt = db_query($sql);
            }           
}

$sql = "
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Lomonosov Moscow State University','Rússia','ASI','http://www.msu.ru/en/','100.0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Saint-Petersburg State University','Rússia','ASI','http://eng.spbu.ru/','70.7',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Novosibirsk State University','Rússia','ASI','http://www.nsu.ru/english/','46.8',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('State University Higher Scholl of Econimics','Rússia','ASI','http://www.hse.ru/en/','34.1',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Tomsk State University','Rússia','ASI','http://www.tusur.ru/en/','37.7',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Ural State University','Rússia','ASI','http://eng.usu.ru/usu/opencms/','32.7',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Kazan State University','Rússia','ASI','http://www.ksu.ru/eng/departments/f5/','30.4',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Indian Institute of Technology Bombay (IITB)','Índia','ASI','http://www.iitb.ac.in/','100.0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Indian Institute of Technology Delhi (IITD)','Índia','ASI','http://www.iitd.ac.in/','97.3',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Jawaharlal Nehur University','Índia','ASI','http://www.jnu.ac.in/','97.3',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Dalhi','Índia','ASI','http://www.du.ac.in/index.php?id=4','64.0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Indian Institute of Technology Roorkee (IITBR)','Índia','ASI','http://www.iitr.ac.in/','53.8',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Calcutta','Índia','ASI','http://www.caluniv.ac.in/','43.9',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Melbourne ','Australia','OCE','http://www.unimelb.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Australia National University','Australia','OCE','http://www.anu.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Curtin University of Technology','Australia','OCE','http://www.curtin.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Deakin University','Australia','OCE','http://deakin.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Flinders University','Australia','OCE','http://www.flinders.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Macquarie University','Australia','OCE','http://mq.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Monash University','Australia','OCE','http://www.monash.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Queensland University of Technology','Australia','OCE','http://www.qut.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('RMIT University','Australia','OCE','http://www.rmit.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of Adelaide','Australia','OCE','http://www.adelaide.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of  Melbourne','Australia','OCE','http://www.unimelb.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of New South Wales','Australia','OCE','http://www.unsw.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of  Queensland','Australia','OCE','http://www.uq.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of Sydney','Australia','OCE','http://sydney.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of Westem Autralia','Australia','OCE','http://www.uwa.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Auckland','Australia','OCE','University of Auckland','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Newcastle','Australia','OCE','http://www.ncl.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of South Australia ','Australia','OCE','http://www.unisa.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values (' University Tasmania','Australia','OCE','http://www.utas.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Technology Sydney','Australia','OCE','http://www.uts.edu.au/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Wollongong','Australia','OCE','http://www.uow.edu.au/index.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Karl-Franzens-Universitaet Graz','Australia','OCE','http://www.uni-graz.at/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Medical University of Vienna','Australia','OCE','http://www.meduniwien.ac.at/index.php?id=372&language=2','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Carleton University','Canada','AMN','http://www.carleton.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Concordia University','Canada','AMN','http://www.concordia.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Dalhousie University','Canada','AMN','http://www.dal.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Laval University','Canada','AMN','http://www2.ulaval.ca/accueil.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('McGill University','Canada','AMN','http://www.mcgill.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('McMaster University','Canada','AMN','http://www.mcmaster.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Queen´s University','Canada','AMN','http://www.queensu.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Simon Fraser University','Canada','AMN','http://www.sfu.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('The University of Westem Ontario','Canada','AMN','http://www.uwo.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Université de Montréal','Canada','AMN','http://www.umontreal.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Alberta','Canada','AMN','http://www.ualberta.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of British Columbia','Canada','AMN','http://www.ubc.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Calgary','Canada','AMN','http://www.ucalgary.ca/community/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Guelph','Canada','AMN','http://www.uoguelph.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Ottawa','Canada','AMN','http://www.uottawa.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Toronto','Canada','AMN','http://www.utoronto.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Victoria','Canada','AMN','http://www.uvic.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Waterloo','Canada','AMN','http://uwaterloo.ca/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('York University','Canada','AMN','http://www.yorku.ca/web/index.htm','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Seoul Natinal University','Coréia do Sul','ASI','http://www.useoul.edu/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('KAIST - Korea Advanced Institute of Science & Technology','Coréia do Sul','ASI','http://www.topuniversities.com/institution/kaist-korea-advanced-institute-science-technology','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Bielefeld University','Alemanha','EUR','http://www.uni-bielefeld.de/(en)/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Christian-Albrechts-Universität zu Kiel','Alemanha','EUR','http://www.uni-kiel.de/index-e.shtml','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Eberhard Karls Universität Tübingen','Alemanha','EUR','http://www.uni-tuebingen.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Freie Universität Berlin','Alemanha','EUR','http://www.fu-berlin.de/en/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Georg-August-Universität Göttingen','Alemanha','EUR','http://www.uni-goettingen.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Humboldt University of Berlin','Alemanha','EUR','http://www.kiepert-hu.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Karlsruhe Institute of Technology','Alemanha','EUR','http://www.kit.edu/english/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Ludwig-Maximilians-Universität Bonn','Alemanha','EUR','https://login.portal.uni-muenchen.de/login/loginapp/login.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Rheinische Friedrich-Wilhelms- Universität Bonn','Alemanha','EUR','http://www.topuniversities.com/institution/rheinische-friedrich-wilhelms-universitaet-bonn','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Rheinisch-Westfälische Technische Hochschule','Alemanha','EUR','Rheinisch-Westfälische Technische Hochschule','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Ruprecht Karl University of Heidelberg','Alemanha','EUR','http://www.uni-heidelberg.de/index_e.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('RWTH Aachen University','Alemanha','EUR','http://www.rwth-aachen.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Technical University of Munich','Alemanha','EUR','http://www.tum.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Technical Universität of  Berlin','Alemanha','EUR','http://www.tu-berlin.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Technical Universität of Darmstadt','Alemanha','EUR','http://www.tu-darmstadt.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Technical Universität of Dresden','Alemanha','EUR','http://www.topuniversities.com/institution/technische-universitaet-dresden','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Technical Universität of München','Alemanha','EUR','http://www.tum.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Bayreuth','Alemanha','EUR','http://www.uni-bayreuth.de/index.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Bremen','Alemanha','EUR','http://www.uni-bremen.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Düsseldorf','Alemanha','EUR','http://www.uni-duesseldorf.de/home/startseite.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Erlangen-Nümberg','Alemanha','EUR','http://www.uni-erlangen.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Frankfurt am Main','Alemanha','EUR','http://www.topuniversities.com/institution/universitaet-frankfurt-am-main','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Freiburg','Alemanha','EUR','http://www.uni-freiburg.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Hamburg','Alemanha','EUR','http://www.uni-hamburg.de/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Karlsruhe','Alemanha','EUR','http://www.kit.edu/index.php','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Universität Stuttgart','Alemanha','EUR','http://www.uni-stuttgart.de/home/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University Göttingen','Alemanha','EUR','http://www.uni-goettingen.de/en/1.html','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University Konstanz','Alemanha','EUR','http://www.uni-konstanz.de/willkommen/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Würzburg','Alemanha','EUR','http://www.uni-wuerzburg.de/en/home/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Johann Wolfgang Goethe University Frankfurt am Main','Alemanha','EUR','http://www2.uni-frankfurt.de/de','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Johannes Gutenberg Universität Mainz','Alemanha','EUR','http://www.uni-mainz.de/eng/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Julius-Maximilians-Universität Würzburg','Alemanha','EUR','http://www.moveonnet.eu/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Birkberck University of London','Reino Unido','EUR','http://www.bbk.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Imperial College London','Reino Unido','EUR','http://www3.imperial.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Durhan University','Reino Unido','EUR','http://www.dur.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('King´s College London','Reino Unido','EUR','http://www.kcl.ac.uk/index.aspx','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Lancaster University','Reino Unido','EUR','http://www.lancs.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('London School of Economics and Political Science','Reino Unido','EUR','http://www2.lse.ac.uk/home.aspx','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Newcastle University','Reino Unido','EUR','http://www.ncl.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Queen Mary University of London','Reino Unido','EUR','http://www.qmul.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Royal Holloway University of London','Reino Unido','EUR','http://www.rhul.ac.uk/home.aspx','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('Iniversity College London','Reino Unido','EUR','http://www.ucl.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Aberdeen','Reino Unido','EUR','http://www.abdn.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Birmingham','Reino Unido','EUR','http://www.birmingham.ac.uk/index.aspx','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Bristol','Reino Unido','EUR','http://www.bris.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Cambridge','Reino Unido','EUR','http://www.cam.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Dundee','Reino Unido','EUR','http://www.dundee.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of East Anglia','Reino Unido','EUR','http://www.uea.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Edinburgh','Reino Unido','EUR','http://www.ed.ac.uk/home','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Exeter','Reino Unido','EUR','http://www.exeter.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Glasgow','Reino Unido','EUR','http://www.gla.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Leeds','Reino Unido','EUR','http://www.leeds.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Liverpool','Reino Unido','EUR','http://www.liv.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Manchester','Reino Unido','EUR','http://www.manchester.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Nottingham','Reino Unido','EUR','http://www.nottingham.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Oxford','Reino Unido','EUR','http://www.ox.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Sheffield','Reino Unido','EUR','http://www.shef.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of York','Reino Unido','EUR','http://www.york.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Southampton','Reino Unido','EUR','http://www.southampton.ac.uk/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Andrews','Reino Unido','EUR','http://www.andrews.edu/','0',1);
insert intro universidades (um_nome, um_pais, um_continente_site,um_link,um_nota, um_ativo) values ('University of Sussex','Reino Unido','EUR','http://www.sussex.ac.uk/','0',1);
";

?>