<!DOCTYPE html>
<html>
<head>
  <title>The VexFlow Tutorial</title>
  
  <!-- VexFlow Uncompiled Sources -->
  <script src="./inc/vexflow/src/header.js"></script>
  <script src="./inc/vexflow/src/vex.js"></script>
  <script src="./inc/vexflow/src/flow.js"></script>
  <script src="./inc/vexflow/src/tables.js"></script>
  <script src="./inc/vexflow/src/fonts/vexflow_font.js"></script>
  <script src="./inc/vexflow/src/glyph.js"></script>
  <script src="./inc/vexflow/src/stave.js"></script>
  <script src="./inc/vexflow/src/staveconnector.js"></script>
  <script src="./inc/vexflow/src/tabstave.js"></script>
  <script src="./inc/vexflow/src/tickcontext.js"></script>
  <script src="./inc/vexflow/src/tickable.js"></script>
  <script src="./inc/vexflow/src/note.js"></script>
  <script src="./inc/vexflow/src/barnote.js"></script>
  <script src="./inc/vexflow/src/ghostnote.js"></script>
  <script src="./inc/vexflow/src/stavenote.js"></script>
  <script src="./inc/vexflow/src/tabnote.js"></script>
  <script src="./inc/vexflow/src/beam.js"></script>
  <script src="./inc/vexflow/src/voice.js"></script>
  <script src="./inc/vexflow/src/voicegroup.js"></script>
  <script src="./inc/vexflow/src/modifier.js"></script>
  <script src="./inc/vexflow/src/modifiercontext.js"></script>
  <script src="./inc/vexflow/src/accidental.js"></script>
  <script src="./inc/vexflow/src/dot.js"></script>
  <script src="./inc/vexflow/src/formatter.js"></script>
  <script src="./inc/vexflow/src/stavetie.js"></script>
  <script src="./inc/vexflow/src/tabtie.js"></script>
  <script src="./inc/vexflow/src/tabslide.js"></script>
  <script src="./inc/vexflow/src/bend.js"></script>
  <script src="./inc/vexflow/src/vibrato.js"></script>
  <script src="./inc/vexflow/src/annotation.js"></script>
  <script src="./inc/vexflow/src/articulation.js"></script>
  <script src="./inc/vexflow/src/tuning.js"></script>
  <script src="./inc/vexflow/src/stavemodifier.js"></script>
  <script src="./inc/vexflow/src/keysignature.js"></script>
  <script src="./inc/vexflow/src/timesignature.js"></script>
  <script src="./inc/vexflow/src/clef.js"></script>
  <script src="./inc/vexflow/src/music.js"></script>
  <script src="./inc/vexflow/src/keymanager.js"></script>
  <script src="./inc/vexflow/src/renderer.js"></script>
  <script src="./inc/vexflow/src/raphaelcontext.js"></script>
  <script src="./inc/vexflow/src/stavevolta.js"></script>
  <script src="./inc/vexflow/src/staverepetition.js"></script>
  <script src="./inc/vexflow/src/stavebarline.js"></script>
  <script src="./inc/vexflow/src/stavesection.js"></script>
  <script src="./inc/vexflow/src/stavehairpin.js"></script>
  <script src="./inc/vexflow/src/stavetempo.js"></script>
  <script src="./inc/vexflow/src/tremolo.js"></script>

  <!-- VexFlow Compiled Source -->
  <script src="../vexflow.js"></script>

  <!-- Support Sources -->
  <script src="./inc/vexflow/jquery.js"></script>
  <script src="./inc/vexflow/raphael.js"></script>
</head>
<body>
  <div class="description one">
  <div class="example a" example="a">
    <canvas width=525 height=120></canvas>
    <script>
      var canvas = $("div.one div.a canvas")[0];
      var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);

      var ctx = renderer.getContext();
      var stave = new Vex.Flow.Stave(10, 0, 500);
      stave.addClef("treble").setContext(ctx).draw();
      
      // Create the notes
      var notes = [
        // A quarter-note C.
        //new Vex.Flow.StaveNote({ keys: ["c/4"], duration: "q" }),
		new Vex.Flow.StaveNote({note0}),

        // A quarter-note D.
        new Vex.Flow.StaveNote({note1}),

        // A quarter-note rest. Note that the key (b/4) specifies the vertical
        // position of the rest.
        new Vex.Flow.StaveNote({note2}),

        // A C-Major chord.
        new Vex.Flow.StaveNote({note3})
      ];

      // Create a voice in 4/4
      var voice = new Vex.Flow.Voice({
        num_beats: 4,
        beat_value: 4,
        resolution: Vex.Flow.RESOLUTION
      });

      // Add notes to voice
      voice.addTickables(notes);

      // Format and justify the notes to 500 pixels
      var formatter = new Vex.Flow.Formatter().
        joinVoices([voice]).format([voice], 500);

      // Render voice
      voice.draw(ctx, stave);
    </script>
  </div>
  </div>
<body>
</html>