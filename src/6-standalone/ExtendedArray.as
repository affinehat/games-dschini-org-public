package
{
	dynamic public class ExtendedArray extends Array
	{
		public function ExtendedArray(... optionalArgs)
		{
			for each (var value:* in optionalArgs){
				super.push(value);
			}
		}
		
		public function _shuffle(startIndex:int = 0, endIndex:int = 0):Array
		{
			if(endIndex == 0){
				endIndex = this.length-1;
			}
			for (var i:int = endIndex; i>startIndex; i--) {
				var randomNumber:int = Math.floor(Math.random()*endIndex)+startIndex;
				var tmp:* = this[i];
				this[i] = this[randomNumber];
				this[randomNumber] = tmp;
			}
			return this;
		}
		
		function __shuffle(a,b):int
		{
			var num : int = Math.round(Math.random()*2)-1;
			return num;
		}
		
		public function shuffle(startIndex:int = 0, endIndex:int = 0):Array
		{
			var ss:Array = this.sort(__shuffle);
			return this.sort(__shuffle);
		}
		
	}
}
