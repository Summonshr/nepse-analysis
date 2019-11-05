import React from "react";

class Layout extends React.Component {
  render() {
    return <div>{this.props.children}</div>;
  }
}


export default Layout;