const Move = class {
  constructor(from, to){
    this.from = from;
    this.to = to;
    this.isValid = this.checkIsValid(from, to);
  }

  letterToCardinalPosition(letter) {
    letter = letter.toUpperCase();
    if (letter.length === 1 && letter >= 'A' && letter <= 'Z') {
      return letter.charCodeAt(0) - 'A'.charCodeAt(0) + 1;
    } else {
      return null;
    }
  }

  checkIsValid(from, to) {
    if(from === ''){return true;}
    const fromX = this.letterToCardinalPosition(from[0]);
    const fromY = parseInt(from[1]);
    const toX = this.letterToCardinalPosition(to[0]);
    const toY = parseInt(to[1]);
    const xDiff = Math.abs(fromX - toX);
    const yDiff = Math.abs(fromY - toY);
    if((xDiff == 2 && yDiff == 1) || (xDiff == 1 && yDiff == 2)){return true;}
    return false;
  }
}

// Bind cell click callbacks.
const bindMoveBehavior = (chessboard, input) => {
  let cells = chessboard.querySelectorAll('td.cb__cell');
  input.value = input.value.toUpperCase();
  let seqState = input.value.split(',');

  // Handle all cell clicks in a clojure so we can access the values of input.
  const handleClick = (event) => {
    const target = event.target;
    const id = target.dataset.cellId;
    const prev = seqState[seqState.length - 1];
    const move = new Move(prev, id);

    // Check that move is possible and unique.
    if(move.isValid === true && (!seqState.includes(id))) {

      // Maintain comma seperation.
      if(input.value.length > 0){
        input.value += ',';
      }

      // Update our state indicators.
      input.value += id;
      seqState.push(id);
      target.classList.add("visited");
    }
  };

  // Bind click events.
  cells.forEach((cell) => {
    cell.addEventListener('click', handleClick);
  });
};

// Bind events and behaviors.
document.addEventListener("DOMContentLoaded", () => {
  const wrapper = document.querySelector("#edit-field-knight-s-tour-wrapper:not(.perec-chessboard-initialized)");

  if(wrapper){
    wrapper.classList.add('perec-chessboard-initialized');
    const chessboard = wrapper.querySelector('table.perec-chessboard');
    const input = wrapper.querySelector('textarea');
    // TODO: Handle updating on-load state here.
    bindMoveBehavior(chessboard, input);
  }

});
