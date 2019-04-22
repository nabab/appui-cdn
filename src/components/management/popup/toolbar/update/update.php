<bbn-table :source="source.list">
  <bbns-column title= "<?=_('Library')?>"
              field= "title"
  ></bbns-column>
  <bbns-column title= "<?=_('Local version')?>"
              field= "local"
              cls= "bbn-c"
  ></bbns-column>
  <bbns-column title= "<?=_('Latest version')?>"
              field= "latest"
              cls= "bbn-c bbn-bg-red bbn-white"
  ></bbns-column>
  <bbns-column title= " "
               width= 140
               field= "title"
               :buttons= "buttons"
               cls= "bbn-c"
  ></bbns-column>
</bbn-table>
