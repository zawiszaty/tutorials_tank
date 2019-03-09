import React, {Component} from 'react';
import AppBar from "@material-ui/core/AppBar";
import Typography from "@material-ui/core/Typography";
import withStyles from "@material-ui/core/es/styles/withStyles";

const styles = theme => ({
  footer: {
      marginTop: '10em'
  }
});

class FooterComponent extends Component {
    render() {
        const {classes} = this.props;
        return (
            <AppBar position="static" color="primary" className={classes.footer}>
                <Typography component="h2" variant="headline" color="inherit" gutterBottom align="center">
                    Strona stworzona przez zespół Tutorials Tank &copy; Copyright 2019
                </Typography>
            </AppBar>
        );
    }
}

FooterComponent.propTypes = {};

export default withStyles(styles, {withTheme: true})(FooterComponent);