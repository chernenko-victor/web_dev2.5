<div class="description {div_outer}">
  <div class="example {div_inner}" example="a">
    <canvas width=525 height=120></canvas>
    <script>
      var canvas = $("div.{div_outer} div.{div_inner} canvas")[0];
      var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);

      var ctx = renderer.getContext();
      var stave = new Vex.Flow.Stave(10, 0, 500);
      stave.addClef("treble").setContext(ctx).draw();
      
      // Create the notes
      var notes = [
        // A quarter-note C.
        //new Vex.Flow.StaveNote({ keys: ["c/4"], duration: "q" }),
		new Vex.Flow.StaveNote({note0}){accid0},

        // A quarter-note D.
        new Vex.Flow.StaveNote({note1}){accid1},

        // A quarter-note rest. Note that the key (b/4) specifies the vertical
        // position of the rest.
        new Vex.Flow.StaveNote({note2}){accid2},

        // A C-Major chord.
        new Vex.Flow.StaveNote({note3}){accid3}
      ];
      /*

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
      */
      Vex.Flow.Formatter.FormatAndDraw(ctx, stave, notes);
    </script>
  </div>
  </div>