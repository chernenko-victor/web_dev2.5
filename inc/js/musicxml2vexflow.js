//musicXML 2 vexflow
	function CreateXMLDocument (serializedXml) 
	{
        var xmlDoc = null;
        if (window.DOMParser) {
            var parser = new DOMParser ();
            xmlDoc = parser.parseFromString (serializedXml, "text/xml");
        } else if (window.ActiveXObject) {
            xmlDoc = new ActiveXObject ("Microsoft.XMLDOM");
            xmlDoc.async = false;
            xmlDoc.loadXML (serializedXml);
        }
		return xmlDoc;
    }
  
    var VexDocument = null;
    var VexFormatter = null;
	
	function ConvMusicxml2Vexflow(strTest) 
	{
		var iRes = 0;
		/*
		strTest = '<?xml version="1.0"?><score-partwise version="3.0"><part-list><score-part id="P1"><part-name>Music</part-name>    </score-part><score-part id="P3"><part-name>Music3</part-name></score-part></part-list><part id="P1"><measure number="1"><attributes><divisions>1</divisions><key><fifths>0</fifths></key><time><beats>4</beats><beat-type>4</beat-type></time><clef><sign>G</sign><line>2</line></clef></attributes><note><pitch><step>C</step><octave>4</octave></pitch><duration>4</duration><type>whole</type></note></measure><measure number="2"><attributes><divisions>1</divisions><key><fifths>3</fifths></key><time><beats>3</beats><beat-type>4</beat-type></time><clef><sign>G</sign><line>2</line></clef></attributes><note><pitch><step>G</step><alter>1</alter><octave>4</octave></pitch><duration>3</duration><type>whole</type></note></measure></part><part id="P3"><measure number="1"><attributes><divisions>1</divisions><key><fifths>0</fifths></key><time><beats>4</beats><beat-type>4</beat-type></time><clef><sign>F</sign><line>4</line></clef></attributes><note><pitch><step>C</step><octave>4</octave></pitch><duration>2</duration><type>half</type></note><note><pitch><step>B</step><alter>-1</alter><octave>3</octave></pitch><duration>2</duration><type>half</type></note></measure></part></score-partwise>';
		*/
		
		/*
		strTest = '<?xml version="1.0" encoding="UTF-8"?>\
<score-partwise version="3.0">\
 <part-list>\
  <score-part id="part1">\
   <part-name>Guit. bass</part-name>\
  </score-part>\
  <score-part id="part2">\
   <part-name>Cl.</part-name>\
  </score-part>\
  <score-part id="part3">\
   <part-name>Fl.</part-name>\
  </score-part>\
  <score-part id="part4">\
   <part-name>Harp.</part-name>\
  </score-part>\
  <score-part id="part5">\
   <part-name>Drum kit</part-name>\
  </score-part>\
  <score-part id="part6">\
   <part-name>Tr-no</part-name>\
  </score-part>\
 </part-list>\
 <part id="part1">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
 <part id="part2">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
 <part id="part3">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
 <part id="part4">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
 <part id="part5">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
 <part id="part6">\
  <measure number="1">\
   <attributes>\
    <divisions>1</divisions>\
    <key>\
     <fifths>0</fifths>\
    </key>\
    <time>\
     <beats>4</beats>\
     <beat-type>1</beat-type>\
    </time>\
    <clef>\
     <sign>G</sign>\
     <line>2</line>\
    </clef>\
   </attributes>\
   <note>\
    <pitch>\
     <step>E</step>\
     <alter>-1</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>F</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
   <note>\
    <pitch>\
     <step>B</step>\
     <alter>0</alter>\
     <octave>4</octave>\
    </pitch>\
    <duration>1</duration>\
    <type>whole</type>\
   </note>\
  </measure>\
 </part>\
</score-partwise>';
		*/
		if(strTest==null)
		{
		    //console.log("exit");
		    iRes = 1;
		}
		else
		{
			var start = new Date().getTime(); // time execution

			
			strTest = strTest.trim();
			console.log("strTest = " + strTest);
			
			data = CreateXMLDocument(strTest);
			//console.log("data = " + data);
			
			VexDocument = new Vex.Flow.Document(data);
			//console.log(VexDocument);
			var content = $(".content")[0];
			if (VexDocument) {
			  VexFormatter = VexDocument.getFormatter();
			  VexFormatter.draw(content);
			}
			var elapsed = (new Date().getTime() - start)/1000;
			var debouncedResize = null;
			$(window).resize(function() {
			  if (! debouncedResize)
				debouncedResize = setTimeout(function() {
				  VexFormatter.draw(content);
				  debouncedResize = null;
				}, 500);
			});
		}
		return iRes;
	}