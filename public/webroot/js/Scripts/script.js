///<reference path="jspack-vsdoc.js" />

var Diagram = MindFusion.Diagramming.Diagram;
var CompositeNode = MindFusion.Diagramming.CompositeNode;
var Behavior = MindFusion.Diagramming.Behavior;
var Events = MindFusion.Diagramming.Events;
var Theme = MindFusion.Diagramming.Theme;
var Style = MindFusion.Diagramming.Style;

var Alignment = MindFusion.Drawing.Alignment;
var Rect = MindFusion.Drawing.Rect;
var Point = MindFusion.Drawing.Point;

var TreeLayout = MindFusion.Graphs.TreeLayout;

var diagram = null;
var names;
var coloredNode;

//The DeanNode is a class that inherits from CompositeNode and 
//uses the available layout panels (grid, stack, simple etc.) and components (text, image, table etc.) 
//to construct the desired node type
var DeanNode = CompositeNode.classFromTemplate("DeanNode",
{
	component: "SimplePanel",
	name: "root",
	children:
	[
        {
            component: "Rect",
			name: "Background",
			brush: "white",
			pen: "#cecece",
		},
		{
			component: "GridPanel",
			rowDefinitions: ["*", "2"],
			columnDefinitions: ["*"],
			children:
			[	
				{		
					component: "StackPanel",
					orientation: "Vertical",
					margin: "1",
					verticalAlignment: "Near",
					gridRow: 0,
					children: [
						{
							component: "Text",
							name: "Faculty",
							autoProperty: true,
							text: "title",
							font: "serif bold"
							},
							{
							component: "Text",
							name: "Dean",
							autoProperty: true,
							text: "Name of dean",
							pen: "#808080",
							padding: "1,0,1,0"
							},
							{
							component: "Text",
							name: "Details",
							autoProperty: true,
							text: "details",
							font: "serif 3.5 italic"
						}		
						
					]
				},
				
				{
					component: "Rect",
					name: "Underline",
					pen: "red",
					brush: "red",
					gridRow: 1,
					autoProperty: true					
				}
			]
		}			
	]
});


document.addEventListener("DOMContentLoaded", function ()
{
	//some random names for the people
	names = ["Nicole Montgomery", "Loren Alvarado", "Vicki Fisher", "Edith Fernandez", "Lynette Sullivan", "Amy Rhodes", "Teresa Marsh", "Ginger Larson", "Bob Lawrence", "Arthur Ball", "Nikhe Niama", "Mitchell Barker", "Jane Silva", "Diana Curry", "Jay Smith", "Caroline Garcia", "Paulette Wells", "Alexander Chapman", "Emanuel Glover", "Shannon Daniel", "Jesus Townsend", "Lowell Gibbs", "Ruben Figueroa", "Estelle Henderson", "Sonja French", "Ken Underwood", "Joe Hines", "Eric Rogers", "Lindsay Manning", "Jorge Shelton", "Bobby Sanders", "Mamie Pratt", "Rudolph Armstrong", "Wayne Mcguire", "Jessica Peters", "Clinton Maxwell", "Lillian Carroll", "Felipe Craig", "Marion Holt", "Willard Reynolds", "Anita Adkins", "Ramona Hanson", "Zachary Rodriguez", "Boyd Todd", "Michelle Ford", "Orlando Jenkins", "Nelson Benson", "Shirley Farmer", "Eddie Curtis", "Phil Taylor", "Yolanda Strickland", "Simon Abbott", "Jesus Neal", "Roman Owens", "Heather Hogan", "Andrew Jennings", "Lucille Kelly", "Glenda Lee", "Kathryn Boone", "Craig Summers", "Michele Fernandez", "Tonya Parsons", "Bennie Freeman", "Stewart Austin", "Johanna Barber", "Julia Dean", "Jeanette Hernandez", "Nicholas Hawkins", "Miriam Lindsey", "Chester Waters", "Marta Jackson", "Jake Brown", "Rufus Turner", "Melvin Nunez", "Luther Collier", "Geraldine Barton", "Wesley Lamb", "Wilbur Frazier", "Wendell Saunders", "Brittany Corte"];
	
	// create a Diagram component that wraps the "diagram" canvas
	diagram = Diagram.create(document.getElementById("diagramCanvas"));
		// enable drawing of custom nodes interactively
	diagram.setCustomNodeType(DeanNode);
	diagram.setBehavior(Behavior.Custom);
	
	var theme = new Theme();
	var linkStyle = new Style();
	linkStyle.setStroke("#CECECE");
	theme.styles["std:DiagramLink"] = linkStyle;
	diagram.setTheme(theme);	
	diagram.setShadowsStyle(MindFusion.Diagramming.ShadowsStyle.None);
	createNodes();
	
	var links = diagram.getLinks();
	
	//set all links to light gray and with pointers at the bottom, 
	//rather than the head in order to appear inverted
	for(var i = 0; i < links.length; i++)
	{
		var link = links[i];
		
	link.setBaseShape("Triangle");
	link.setHeadShape(null);
	link.setBaseShapeSize(3.0);
	link.setBaseBrush({ type: 'SolidBrush', color: "#CECECE" });
	link.setZIndex(0);
	}
	
	//create an instance of the Tree Layout and apply it
	var layout = new TreeLayout();
	layout.direction = MindFusion.Graphs.LayoutDirection.TopToBottom;
	layout.linkType = MindFusion.Graphs.TreeLayoutLinkType.Cascading;
	//enabling assistants tells the layout to order the nodes with Assistant traits in a special way
	layout.enableAssistants = true;
    diagram.arrange(layout);
	
	diagram.resizeToFitItems(5);
	
		// create an ZoomControl component that wraps the "zoomer" canvas
	var zoomer = MindFusion.Controls.ZoomControl.create(document.getElementById("zoomer"));
	zoomer.setTarget(diagram);
	zoomer.setZoomFactor(55);	
	
	
});

//create all nodes
function createNodes()
{	
	var xxpNodeMath = new DeanNode(diagram);
	xxpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	xxpNodeMath.setFaculty("DEPARTEMENT DEVELOPPEMENT FORMATION ET CI");
	xxpNodeMath.setDean("M. YOBAT");
	xxpNodeMath.setDetails(null);
	xxpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	xxpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(xxpNodeMath);

	var yypNodeMath = new DeanNode(diagram);
	yypNodeMath.setBounds(new Rect(80, 225, 60, 25));
	yypNodeMath.setFaculty("DEPARTEMENT ADMINISTRATION ET RELATIONS SOCIALES");
	yypNodeMath.setDean("(A pourvoir)");
	yypNodeMath.setDetails(null);
	yypNodeMath.getComponent("Underline").brush = "#d7bb9c";
	yypNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(yypNodeMath);
	diagram.getFactory().createDiagramLink(yypNodeMath, xxpNodeMath);

	var vpNodeMath = new DeanNode(diagram);
	vpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	vpNodeMath.setFaculty("DIRECTION CAPITAL HUMAIN");
	vpNodeMath.setDean("Mme. KORILA");
	vpNodeMath.setDetails(null);
	vpNodeMath.getComponent("Underline").brush = "#b3f0a9";
	vpNodeMath.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(vpNodeMath);
	diagram.getFactory().createDiagramLink(vpNodeMath, yypNodeMath);

	var zzpNodeMath = new DeanNode(diagram);
	zzpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	zzpNodeMath.setFaculty("DEPARTEMENT LITIGES");
	zzpNodeMath.setDean("Mme. NGOLA");
	zzpNodeMath.setDetails(null);
	zzpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	zzpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(zzpNodeMath);

	var aaapNodeMath = new DeanNode(diagram);
	aaapNodeMath.setBounds(new Rect(80, 225, 60, 25));
	aaapNodeMath.setFaculty("DEPARTEMENT GARANTIES");
	aaapNodeMath.setDean("M. MAVOUNGOU");
	aaapNodeMath.setDetails(null);
	aaapNodeMath.getComponent("Underline").brush = "#d7bb9c";
	aaapNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(aaapNodeMath);
	diagram.getFactory().createDiagramLink(aaapNodeMath, zzpNodeMath);

	var apNodeMath = new DeanNode(diagram);
	apNodeMath.setBounds(new Rect(80, 225, 60, 25));
	apNodeMath.setFaculty("DIRECTION DES AFFAIRES JURIDIQUES");
	apNodeMath.setDean("M. ELENGA");
	apNodeMath.setDetails(null);
	apNodeMath.getComponent("Underline").brush = "#b3f0a9";
	apNodeMath.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(apNodeMath);
	diagram.getFactory().createDiagramLink(apNodeMath, aaapNodeMath);

	var bbbpNodeMath = new DeanNode(diagram);
	bbbpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	bbbpNodeMath.setFaculty("DEPARTEMENT RECOUVREMENT CTX ENTREPRISE");
	bbbpNodeMath.setDean("Mme. EFONOUAH");
	bbbpNodeMath.setDetails(null);
	bbbpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	bbbpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(bbbpNodeMath);

	var cccpNodeMath = new DeanNode(diagram);
	cccpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	cccpNodeMath.setFaculty("DEPARTEMENT RECOUVREMENT CTX PARTICULIERS");
	cccpNodeMath.setDean("Mme. PASSY HANGA");
	cccpNodeMath.setDetails(null);
	cccpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	cccpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(cccpNodeMath);
	diagram.getFactory().createDiagramLink(cccpNodeMath, bbbpNodeMath);

	var bpNodeMath = new DeanNode(diagram);
	bpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	bpNodeMath.setFaculty("DIRECTION RECOUVREMENT CONTENTIEUX");
	bpNodeMath.setDean("Mme. TAMBA");
	bpNodeMath.setDetails(null);
	bpNodeMath.getComponent("Underline").brush = "#b3f0a9";
	bpNodeMath.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(bpNodeMath);
	diagram.getFactory().createDiagramLink(bpNodeMath, cccpNodeMath);

	var cpNodeMath = new DeanNode(diagram);
	cpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	cpNodeMath.setFaculty("DEPARTEMENT COMMUNICATION INSTITUTIONNELLE ET LCB ACADEMY");
	cpNodeMath.setDean("M. SAMOUE");
	cpNodeMath.setDetails(null);
	cpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	cpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(cpNodeMath);

	var dpNodeMath = new DeanNode(diagram);
	dpNodeMath.setBounds(new Rect(80, 225, 60, 25));
	dpNodeMath.setFaculty("DEPARTEMENT SECURITE PHYSIQUE");
	dpNodeMath.setDean("M. MAWANA");
	dpNodeMath.setDetails(null);
	dpNodeMath.getComponent("Underline").brush = "#d7bb9c";
	dpNodeMath.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(dpNodeMath);
	
	var apNodeMed = new DeanNode(diagram);
	apNodeMed.setBounds(new Rect(80, 225, 60, 25));
	apNodeMed.setFaculty("SECRETARIAT GENERAL");
	apNodeMed.setDean("M. DIATHA");
	apNodeMed.setDetails(null);
	apNodeMed.getComponent("Underline").brush = "#d99898";
	apNodeMed.getComponent("Underline").pen = "#d99898";
	diagram.addItem(apNodeMed);
	diagram.getFactory().createDiagramLink(apNodeMed, vpNodeMath);
	diagram.getFactory().createDiagramLink(apNodeMed, apNodeMath);
	diagram.getFactory().createDiagramLink(apNodeMed, bpNodeMath);
	diagram.getFactory().createDiagramLink(apNodeMed, cpNodeMath);
	diagram.getFactory().createDiagramLink(apNodeMed, dpNodeMath);

	var bpNodeMed = new DeanNode(diagram);
	bpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	bpNodeMed.setFaculty("DEPARTEMENT LOGISTIQUE, ACHAT ET PATRIMOINE");
	bpNodeMed.setDean("M. NKELEKE");
	bpNodeMed.setDetails(null);
	bpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	bpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(bpNodeMed);

	var cpNodeMed = new DeanNode(diagram);
	cpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	cpNodeMed.setFaculty("DEPARTEMENT CONFORMITE");
	cpNodeMed.setDean("M. MOUNIANGA");
	cpNodeMed.setDetails(null);
	cpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	cpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(cpNodeMed);

	var dpNodeMed = new DeanNode(diagram);
	dpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	dpNodeMed.setFaculty("DEPARTEMENT MARKETING, PRODUITS, MARCHES ET PILOTAGE");
	dpNodeMed.setDean("M. NGOULOUKI");
	dpNodeMed.setDetails(null);
	dpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	dpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(dpNodeMed);

	var bbpNodeMed = new DeanNode(diagram);
	bbpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	bbpNodeMed.setFaculty("DEPARTEMENT INSPECTION");
	bbpNodeMed.setDean("M. KIYINDOU");
	bbpNodeMed.setDetails(null);
	bbpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	bbpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(bbpNodeMed);
	
	var aapNodeMed = new DeanNode(diagram);
	aapNodeMed.setBounds(new Rect(80, 225, 60, 25));
	aapNodeMed.setFaculty("DEPARTEMENT INTERNE");
	aapNodeMed.setDean("M. MALONGA");
	aapNodeMed.setDetails(null);
	aapNodeMed.getComponent("Underline").brush = "#d7bb9c";
	aapNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(aapNodeMed);
	diagram.getFactory().createDiagramLink(aapNodeMed, bbpNodeMed);

	var epNodeMed = new DeanNode(diagram);
	epNodeMed.setBounds(new Rect(80, 225, 60, 25));
	epNodeMed.setFaculty("DIRECTION DE L'AUDIT INTERNE ET DE L'INSPECTION GENERALE");
	epNodeMed.setDean("M. ALKAMA");
	epNodeMed.setDetails(null);
	epNodeMed.getComponent("Underline").brush = "#b3f0a9";
	epNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(epNodeMed);
	diagram.getFactory().createDiagramLink(epNodeMed, aapNodeMed);

	var ccpNodeMed = new DeanNode(diagram);
	ccpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ccpNodeMed.setFaculty("CRC");
	ccpNodeMed.setDean(null);
	ccpNodeMed.setDetails(null);
	ccpNodeMed.getComponent("Underline").brush = "#d2d2d2";
	ccpNodeMed.getComponent("Underline").pen = "#d2d2d2";
	diagram.addItem(ccpNodeMed);

	var ddpNodeMed = new DeanNode(diagram);
	ddpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ddpNodeMed.setFaculty("DEPARTEMENT BACK OFFICE DIGITAL");
	ddpNodeMed.setDean("Mme. OPARI");
	ddpNodeMed.setDetails(null);
	ddpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	ddpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(ddpNodeMed);
	diagram.getFactory().createDiagramLink(ddpNodeMed, ccpNodeMed);

	var fpNodeMed = new DeanNode(diagram);
	fpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	fpNodeMed.setFaculty("DIRECTION BANQUE DIGITALE ET W.U");
	fpNodeMed.setDean("M. BAMBI-BONE");
	fpNodeMed.setDetails(null);
	fpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	fpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(fpNodeMed);
	diagram.getFactory().createDiagramLink(fpNodeMed, ddpNodeMed);

	var eepNodeMed = new DeanNode(diagram);
	eepNodeMed.setBounds(new Rect(80, 225, 60, 25));
	eepNodeMed.setFaculty("GROUPE BZV");
	eepNodeMed.setDean(null);
	eepNodeMed.setDetails(null);
	eepNodeMed.getComponent("Underline").brush = "#62adef";
	eepNodeMed.getComponent("Underline").pen = "#62adef";
	diagram.addItem(eepNodeMed);

	var ffpNodeMed = new DeanNode(diagram);
	ffpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ffpNodeMed.setFaculty("GROUPE PNR");
	ffpNodeMed.setDean(null);
	ffpNodeMed.setDetails(null);
	ffpNodeMed.getComponent("Underline").brush = "#62adef";
	ffpNodeMed.getComponent("Underline").pen = "#62adef";
	diagram.addItem(ffpNodeMed);
	diagram.getFactory().createDiagramLink(ffpNodeMed, eepNodeMed);

	var iipNodeMed = new DeanNode(diagram);
	iipNodeMed.setBounds(new Rect(80, 225, 60, 25));
	iipNodeMed.setFaculty("CAF PNR");
	iipNodeMed.setDean(null);
	iipNodeMed.setDetails(null);
	iipNodeMed.getComponent("Underline").brush = "#62adef";
	iipNodeMed.getComponent("Underline").pen = "#62adef";
	diagram.addItem(iipNodeMed);

	var hhpNodeMed = new DeanNode(diagram);
	hhpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	hhpNodeMed.setFaculty("CAF BZV");
	hhpNodeMed.setDean(null);
	hhpNodeMed.setDetails(null);
	hhpNodeMed.getComponent("Underline").brush = "#62adef";
	hhpNodeMed.getComponent("Underline").pen = "#62adef";
	diagram.addItem(hhpNodeMed);
	diagram.getFactory().createDiagramLink(hhpNodeMed, iipNodeMed);

	var ggpNodeMed = new DeanNode(diagram);
	ggpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ggpNodeMed.setFaculty("DEPARTEMENT RECOUVREMENT PARTICULIERS ET ENTREPRISE");
	ggpNodeMed.setDean("Mme. MAHOUNGOU");
	ggpNodeMed.setDetails(null);
	ggpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	ggpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(ggpNodeMed);
	diagram.getFactory().createDiagramLink(ggpNodeMed, ffpNodeMed);
	diagram.getFactory().createDiagramLink(ggpNodeMed, hhpNodeMed);

	var gpNodeMed = new DeanNode(diagram);
	gpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	gpNodeMed.setFaculty("DIRECTION RESEAU PARTICULIERS ET PROFESSIONELS");
	gpNodeMed.setDean("M. KEBA");
	gpNodeMed.setDetails(null);
	gpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	gpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(gpNodeMed);
	diagram.getFactory().createDiagramLink(gpNodeMed, ggpNodeMed);

	var hpNodeMed = new DeanNode(diagram);
	hpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	hpNodeMed.setFaculty("DIRECTION PME ET CORPORATE");
	hpNodeMed.setDean("M. MIOMBE");
	hpNodeMed.setDetails(null);
	hpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	hpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(hpNodeMed);
	diagram.getFactory().createDiagramLink(hpNodeMed, ggpNodeMed);

	var jjpNodeMed = new DeanNode(diagram);
	jjpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	jjpNodeMed.setFaculty("DEPARTEMENT RISQUE OPERATIONELS");
	jjpNodeMed.setDean("M. MABIKA");
	jjpNodeMed.setDetails(null);
	jjpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	jjpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(jjpNodeMed);

	var kkpNodeMed = new DeanNode(diagram);
	kkpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	kkpNodeMed.setFaculty("DEPARTEMENT ANALYSE ET EVALUATION CREDITS");
	kkpNodeMed.setDean("M. MASSENGO");
	kkpNodeMed.setDetails(null);
	kkpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	kkpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(kkpNodeMed);
	diagram.getFactory().createDiagramLink(kkpNodeMed, jjpNodeMed);

	var llpNodeMed = new DeanNode(diagram);
	llpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	llpNodeMed.setFaculty("DEPARTEMENT RISQUE DE CREDIT");
	llpNodeMed.setDean("M. AYA");
	llpNodeMed.setDetails(null);
	llpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	llpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(llpNodeMed);
	diagram.getFactory().createDiagramLink(llpNodeMed, kkpNodeMed);

	var ipNodeMed = new DeanNode(diagram);
	ipNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ipNodeMed.setFaculty("DIRECTION RISQUE MANAGEMENT");
	ipNodeMed.setDean("M. BISSE");
	ipNodeMed.setDetails(null);
	ipNodeMed.getComponent("Underline").brush = "#b3f0a9";
	ipNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(ipNodeMed);
	diagram.getFactory().createDiagramLink(ipNodeMed, llpNodeMed);

	var nnpNodeMed = new DeanNode(diagram);
	nnpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	nnpNodeMed.setFaculty("DEPARTEMENT GAC");
	nnpNodeMed.setDean("Mme. KAMIGNA");
	nnpNodeMed.setDetails(null);
	nnpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	nnpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(nnpNodeMed);

	var oopNodeMed = new DeanNode(diagram);
	oopNodeMed.setBounds(new Rect(80, 225, 60, 25));
	oopNodeMed.setFaculty("DEPARTEMENT OP, BO, MONETIQUE ET COMMERCE EXTERIEUR");
	oopNodeMed.setDean("M. LOKO");
	oopNodeMed.setDetails(null);
	oopNodeMed.getComponent("Underline").brush = "#d7bb9c";
	oopNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(oopNodeMed);
	diagram.getFactory().createDiagramLink(oopNodeMed, nnpNodeMed);

	var jpNodeMed = new DeanNode(diagram);
	jpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	jpNodeMed.setFaculty("DIRECTION TRAITEMENT DES OPERATIONS");
	jpNodeMed.setDean("M. POATY");
	jpNodeMed.setDetails(null);
	jpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	jpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(jpNodeMed);
	diagram.getFactory().createDiagramLink(jpNodeMed, oopNodeMed);

	var pppNodeMed = new DeanNode(diagram);
	pppNodeMed.setBounds(new Rect(80, 225, 60, 25));
	pppNodeMed.setFaculty("DEPARTEMENT EXPLOITATION ET SECURITE");
	pppNodeMed.setDean("M. BANACKISSA");
	pppNodeMed.setDetails(null);
	pppNodeMed.getComponent("Underline").brush = "#d7bb9c";
	pppNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(pppNodeMed);

	var qqpNodeMed = new DeanNode(diagram);
	qqpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	qqpNodeMed.setFaculty("DEPARTEMENT ORGANISATION ET QUALITE");
	qqpNodeMed.setDean("M. APELE");
	qqpNodeMed.setDetails(null);
	qqpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	qqpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(qqpNodeMed);
	diagram.getFactory().createDiagramLink(qqpNodeMed, pppNodeMed);

	var rrpNodeMed = new DeanNode(diagram);
	rrpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	rrpNodeMed.setFaculty("DEPARTEMENT ASSISTANCE MAITRISE D'OUVRAGE");
	rrpNodeMed.setDean("M. MVOUALA");
	rrpNodeMed.setDetails(null);
	rrpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	rrpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(rrpNodeMed);
	diagram.getFactory().createDiagramLink(rrpNodeMed, qqpNodeMed);

	var kpNodeMed = new DeanNode(diagram);
	kpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	kpNodeMed.setFaculty("DIRECTION ORGANISATION ET SI");
	kpNodeMed.setDean("M. KABBAJ");
	kpNodeMed.setDetails(null);
	kpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	kpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(kpNodeMed);
	diagram.getFactory().createDiagramLink(kpNodeMed, rrpNodeMed);

	var sspNodeMed = new DeanNode(diagram);
	sspNodeMed.setBounds(new Rect(80, 225, 60, 25));
	sspNodeMed.setFaculty("DEPARTEMENT TRESORERIE ET ALM");
	sspNodeMed.setDean("M. MAMBEKA");
	sspNodeMed.setDetails(null);
	sspNodeMed.getComponent("Underline").brush = "#d7bb9c";
	sspNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(sspNodeMed);

	var ttpNodeMed = new DeanNode(diagram);
	ttpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	ttpNodeMed.setFaculty("DEPARTEMENT CONTROLE DE GESTION");
	ttpNodeMed.setDean("M. LOEMBA SAFOUESS");
	ttpNodeMed.setDetails(null);
	ttpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	ttpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(ttpNodeMed);
	diagram.getFactory().createDiagramLink(ttpNodeMed, sspNodeMed);

	var uupNodeMed = new DeanNode(diagram);
	uupNodeMed.setBounds(new Rect(80, 225, 60, 25));
	uupNodeMed.setFaculty("DEPARTEMENT CONTROLE COMPTABLE ET FISCALITE");
	uupNodeMed.setDean("M. LOCKOLO");
	uupNodeMed.setDetails(null);
	uupNodeMed.getComponent("Underline").brush = "#d7bb9c";
	uupNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(uupNodeMed);
	diagram.getFactory().createDiagramLink(uupNodeMed, ttpNodeMed);

	var lpNodeMed = new DeanNode(diagram);
	lpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	lpNodeMed.setFaculty("DIRECTION DES AFFAIRES FINANCIERES");
	lpNodeMed.setDean("M. ALFEDDY");
	lpNodeMed.setDetails(null);
	lpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	lpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(lpNodeMed);
	diagram.getFactory().createDiagramLink(lpNodeMed, uupNodeMed);

	var vvpNodeMed = new DeanNode(diagram);
	vvpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	vvpNodeMed.setFaculty("DEPARTEMENT AGENCES");
	vvpNodeMed.setDean("Mme. NGOLA");
	vvpNodeMed.setDetails(null);
	vvpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	vvpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(vvpNodeMed);

	var wwpNodeMed = new DeanNode(diagram);
	wwpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	wwpNodeMed.setFaculty("DEPARTEMENT SERVICES CENTRAUX");
	wwpNodeMed.setDean("M. DENGUET ATTICKY");
	wwpNodeMed.setDetails(null);
	wwpNodeMed.getComponent("Underline").brush = "#d7bb9c";
	wwpNodeMed.getComponent("Underline").pen = "#d7bb9c";
	diagram.addItem(wwpNodeMed);
	diagram.getFactory().createDiagramLink(wwpNodeMed, vvpNodeMed);

	var mpNodeMed = new DeanNode(diagram);
	mpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	mpNodeMed.setFaculty("DIRECTION CONTRÔLE PERMANENT");
	mpNodeMed.setDean("M. YOKA");
	mpNodeMed.setDetails(null);
	mpNodeMed.getComponent("Underline").brush = "#b3f0a9";
	mpNodeMed.getComponent("Underline").pen = "#b3f0a9";
	diagram.addItem(mpNodeMed);
	diagram.getFactory().createDiagramLink(mpNodeMed, wwpNodeMed);

	var vpNodeMed = new DeanNode(diagram);
	vpNodeMed.setBounds(new Rect(80, 225, 60, 25));
	vpNodeMed.setFaculty("DIRECTEUR GENERAL ADJOINT");
	vpNodeMed.setDean("M. RAISSI");
	vpNodeMed.setDetails(null);
	vpNodeMed.getComponent("Underline").brush = "#76A68F";
	vpNodeMed.getComponent("Underline").pen = "#76A68F";
	diagram.addItem(vpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, apNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, bpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, cpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, dpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, epNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, fpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, gpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, hpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, ipNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, jpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, kpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, lpNodeMed);
	diagram.getFactory().createDiagramLink(vpNodeMed, mpNodeMed);
	
	var apNodeStud = new DeanNode(diagram);
	apNodeStud.setBounds(new Rect(80, 225, 60, 25));
	apNodeStud.setFaculty("ADMINISTRATEUR DIRECTEUR GENERAL");
	apNodeStud.setDean("M. BENJELLOUN");
	apNodeStud.setDetails(null);
	apNodeStud.getComponent("Underline").brush = "#76A68F";
	apNodeStud.getComponent("Underline").pen = "#76A68F";
	diagram.addItem(apNodeStud);
	diagram.getFactory().createDiagramLink(apNodeStud, vpNodeMed);

	var vpNodeStud = new DeanNode(diagram);
	vpNodeStud.setBounds(new Rect(80, 225, 60, 25));
	vpNodeStud.setFaculty("LCB CAPITAL");
	vpNodeStud.setDean(null);
	vpNodeStud.setDetails(null);
	vpNodeStud.getComponent("Underline").brush = "#c1a1d4";
	vpNodeStud.getComponent("Underline").pen = "#c1a1d4";
	diagram.addItem(vpNodeStud);
	diagram.getFactory().createDiagramLink(vpNodeStud, apNodeStud);
	
	var pNode = new DeanNode(diagram);
	pNode.setBounds(new Rect(80, 225, 60, 25));
	pNode.setFaculty("COMITE GOUVERNANCE STRATEGIE DEVELOPPEMENT");
	pNode.setDean(null);
	pNode.setDetails(null);
	pNode.getComponent("Underline").brush = "#649AC4";
	pNode.getComponent("Underline").pen = "#649AC4";
	diagram.addItem(pNode);

	diagram.getFactory().createDiagramLink(pNode, vpNodeStud);

	var aNode = new DeanNode(diagram);
	aNode.setBounds(new Rect(80, 225, 60, 25));
	aNode.setFaculty("FONDATION LCB");
	aNode.setDean(null);
	aNode.setDetails(null);
	aNode.getComponent("Underline").brush = "#649AC4";
	aNode.getComponent("Underline").pen = "#649AC4";
	diagram.addItem(aNode);

	var bNode = new DeanNode(diagram);
	bNode.setBounds(new Rect(80, 225, 60, 25));
	bNode.setFaculty("COMITE DE NOMINATION ET REMUNERATION");
	bNode.setDean(null);
	bNode.setDetails(null);
	bNode.getComponent("Underline").brush = "#649AC4";
	bNode.getComponent("Underline").pen = "#649AC4";
	diagram.addItem(bNode);

	var cNode = new DeanNode(diagram);
	cNode.setBounds(new Rect(80, 225, 60, 25));
	cNode.setFaculty("COMITE D'AUDIT ET DE CONTROL INTERNE");
	cNode.setDean(null);
	cNode.setDetails(null);
	cNode.getComponent("Underline").brush = "#649AC4";
	cNode.getComponent("Underline").pen = "#649AC4";
	diagram.addItem(cNode);

	var dNode = new DeanNode(diagram);
	dNode.setBounds(new Rect(80, 225, 60, 25));
	dNode.setFaculty("COMITE RISQUE");
	dNode.setDean(null);
	dNode.setDetails(null);
	dNode.getComponent("Underline").brush = "#649AC4";
	dNode.getComponent("Underline").pen = "#649AC4";
	diagram.addItem(dNode);
	
	var regNode = new DeanNode(diagram);
	regNode.setBounds(new Rect(80, 225, 60, 25));
	regNode.setFaculty("CONSEIL D'ADMINISTRATION");
	regNode.setDean(null);
	regNode.setDetails(null);
	regNode.getComponent("Underline").brush = "#A19AD4";
	regNode.getComponent("Underline").pen = "#A19AD4";
	diagram.addItem(regNode);
	
	diagram.getFactory().createDiagramLink(regNode, pNode);
	diagram.getFactory().createDiagramLink(regNode, aNode);
	diagram.getFactory().createDiagramLink(regNode, bNode);
	diagram.getFactory().createDiagramLink(regNode, cNode);
	diagram.getFactory().createDiagramLink(regNode, dNode);
}


//create the nodes for the given department
function createDepartmentNodes (department, specialities)
{
	var nodes = [];
	
	//create nodes for all specialities in it
	for(var i = 0; i < specialities.length; i++)
	{
		var node = new DeanNode(diagram);
		node.setBounds(new Rect(100, 195, 60, 25));
		node.setFaculty("Faculty of " + department);
		node.setDean(names.pop());
		node.setDetails(
		    getTitle() + " " + specialities[i] + "\r\n" +
			getQualification() + ", " + specialities[i]);
			
	    if(node.getDetails().length > 70)
			node.setDetails(
		    getTitle() + " " + specialities[i]);
		node.getComponent("Underline").brush = "#F2C3A7";
		node.getComponent("Underline").pen = "#F2C3A7";
		node.layoutTraits = { treeLayoutAssistant: MindFusion.Graphs.AssistantType.Left };
		node.setId(specialities[i]);
		diagram.addItem(node);	
		nodes.push(node);
	}
	
	//create the dean of the department
	var major = department;
	
	if(specialities.length > 0)
		major = specialities[0];
	
	var deanNode = new DeanNode(diagram);
	deanNode.setBounds(new Rect(100, 195, 60, 25));
	deanNode.setFaculty("Faculty of " + department);
	deanNode.setDean(names.pop());
	deanNode.setDetails(
		"Dean of the Faculty of " + department+"\r\n" +
		"Professor in " + major);
	if(deanNode.getDetails().length > 60)
		deanNode.setDetails(
		"Dean of the Faculty of " + department);
		
	deanNode.getComponent("Underline").brush = "#F27649";
	deanNode.getComponent("Underline").pen = "#F27649";
	deanNode.setId(department);
	
	diagram.addItem(deanNode);	
	
	//connect the departments with the dean node
	for(var i = 0; i < nodes.length; i++)
	{
		diagram.getFactory().createDiagramLink(deanNode, nodes[i]);
	}
	
	return deanNode;	
	
}

//create the nodes of the colleges
function createCollegeNodes()
{
	var colleges = ["Mathematics", "Information Technology", "Physics", "Mechanics"];
	
	var collegeNodes = [];
	
	for(var i = 0; i < colleges.length; i++)
	{
		var node = new DeanNode(diagram);
		node.setBounds(new Rect(100, 195, 60, 25));
		node.setFaculty("High School of Math and Natural Sciences");
		node.setDean("Accreditied College");
		node.setDetails(
		    "Specialization in " + colleges[i]);	   
		node.getComponent("Underline").brush = "#F2D479";
		node.getComponent("Underline").pen = "#F2D479";
		node.layoutTraits = { treeLayoutAssistant: MindFusion.Graphs.AssistantType.Left };
		node.setId(colleges[i]);
		diagram.addItem(node);			
		collegeNodes.push(node);
	}	
	
	
	//connect the colleges to the last node in the IT department
	for(var i = 0; i < collegeNodes.length; i++)	{
		
		diagram.getFactory().createDiagramLink(getDean(diagram.getNodes(), "Pedagogics for IT and Math"), collegeNodes[i]);
	}
	
	//create colleges in BA and connect the nodes to the last node in the BA and Economics college
	colleges = ["Finance", "Accounting", "Trade", "Banks", "Insurance"];
	
	collegeNodes = [];
	
	for(var i = 0; i < colleges.length; i++)
	{
		var node = new DeanNode(diagram);
		node.setBounds(new Rect(100, 195, 60, 25));
		node.setFaculty("High School of Finance and Accounting");
		node.setDean("Accredited College");
		node.setDetails(
		    "Specialization in " + colleges[i]);	   
		node.getComponent("Underline").brush = "#F2D479";
		node.getComponent("Underline").pen = "#F2D479";
		node.layoutTraits = { treeLayoutAssistant: MindFusion.Graphs.AssistantType.Left };
		node.setId(colleges[i]);
		diagram.addItem(node);			
		collegeNodes.push(node);
	}	
	
	
	for(var i = 0; i < collegeNodes.length; i++)	{
		
		diagram.getFactory().createDiagramLink(getDean(diagram.getNodes(), "Business Management"), collegeNodes[i]);
	}
	
	
}

//search for the dean of the provided departent
function getDean(deanNodes, department)
{
	
	for(var i = 0; i < deanNodes.length; i++)
		if(deanNodes[i].getId() == department)
			return deanNodes[i];
		
		return null;
}

//get random title for the dean
function getTitle()
{
	var titles = ["Program Director for", "Department Chair for", "Associate Dean for", "Assistant Director for"];
	
	var index = Math.floor(Math.random() * titles.length); 
	
	return titles[index];
}

//get a sample education degree for the dean on a ranodm principle
function getQualification()
{
	var titles = ["Clinical Professor", "Associate Professor", "Clinical Associate Professor", "Research Professor", "Professor", "Assistant Professor", "Clinical Assistant Professor"];
	
	var index = Math.floor(Math.random() * titles.length); 
	
	return titles[index];
}

//handles the mouse move event
diagramCanvas.addEventListener('mousemove', function (e) {
	
	//get the position of the mouse
	var cursor = MindFusion.Diagramming.Utils.getCursorPos(e, document.getElementById("diagramCanvas"));
	//convert the mouse position to diagram units
	var point = diagram.clientToDoc(cursor);

//see if there is a diagram node at this location
    var deanNode = diagram.getNodeAt(point);
    if (deanNode) {	
	
	//if there is a node but also another node is colored, we must reset ALL nodes
	 if(coloredNode)
		  resetAllItems();
	  
	 coloredNode = deanNode;	
	  
	  //set the background of the node to the color of its bottom line
      var brush = deanNode.getComponent("Underline").brush;	
	  deanNode.getComponent("Background").brush = brush;	
	
	//set all incoming and outgoing links to be red
	  var links = deanNode.getOutgoingLinks();	  
	  for(var i =0; i < links.length; i++)
	  {
		  var link = links[i];
		  link.setStroke("red");	
		  link.setZIndex(1);
	  }
	  
	  links = deanNode.getIncomingLinks();	  
	  for(var i =0; i < links.length; i++)
	  {
		  var link = links[i];
		  link.setStroke("red");	
		  link.setZIndex(1);		  
	  }  
	  
	  //invalidate the node to repaint it
	  deanNode.invalidate();
	 }else if (coloredNode)
	 {
		 //if we have a colored node and the mouse is not under another node
		 //we ust reset the colors of the colored node and its links
		 coloredNode.getComponent("Background").brush = "white";	
		 
		var links = coloredNode.getOutgoingLinks();	  
		 
	  for(var i =0; i < links.length; i++)
	  {
		  var link = links[i];
		  link.setStroke("#CECECE");
		  link.setZIndex(0);
		  
	  }
	  
	  links = coloredNode.getIncomingLinks();
	  
	  for(var i =0; i < links.length; i++)
	  {
		  var link = links[i];
		  link.setStroke("#CECECE");
		  link.setZIndex(0);
	  }	  
	  
		 coloredNode.invalidate();
		 coloredNode = null;
	 }
}); 


/* sets the background of all nodes to white,
the links to gray and the zIndex of all elements to 0 */
function resetAllItems()
{
	 var nodes = diagram.getNodes();
		  
		  for(var i = 0; i < nodes.length; i++)
			  nodes[i].getComponent("Background").brush = "white";
		  
		  var links = diagram.getLinks();
		  
		   for(var i = 0; i < links.length; i++)
		   {
			  links[i].setStroke("#CECECE");
			  links[i].setZIndex(0);
		   }
}