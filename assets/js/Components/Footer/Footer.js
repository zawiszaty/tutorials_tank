import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';

const styles = theme => ({
    footer: {
        marginTop: '20px',
        textAlign: 'center',
        backgroundColor: '#3f51b5',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#fff'
    }
});

class Footer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <Paper className={classes.footer} color="primary">
                    Wszelkie prawa zastrzeżone
                </Paper>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(Footer);