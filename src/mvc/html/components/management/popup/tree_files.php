<div class="bbn-full-screen">
  <bbn-input placeholder="select file"> </bbn-input>
  <bbn-tree :source="source.tree"
            :map="mapMenu"
            @select="selectElement"
  ></bbn-tree>
</div>
