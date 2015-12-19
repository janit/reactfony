"use strict";

var Timer = React.createClass({
  displayName: "Timer",

  getInitialState: function getInitialState() {
    return { secondsElapsed: this.props.startTime };
  },
  tick: function tick() {
    this.setState({ secondsElapsed: this.state.secondsElapsed + 1 });
  },
  componentDidMount: function componentDidMount() {
    this.interval = setInterval(this.tick, 1000);
  },
  componentWillUnmount: function componentWillUnmount() {
    clearInterval(this.interval);
  },
  render: function render() {
    return React.createElement(
      "div",
      null,
      "Seconds: ",
      this.state.secondsElapsed
    );
  }
});