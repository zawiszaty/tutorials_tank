import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import CircularProgress from "@material-ui/core/CircularProgress/CircularProgress";
import axios from './../../axios';
import TablePagination from "@material-ui/core/TablePagination/TablePagination";
import TableRow from "@material-ui/core/TableRow/TableRow";
import IconButton from "@material-ui/core/IconButton/IconButton";
import FirstPageIcon from "@material-ui/core/SvgIcon/SvgIcon";
import KeyboardArrowRight from '@material-ui/icons/KeyboardArrowRight';
import KeyboardArrowLeft from '@material-ui/icons/KeyboardArrowLeft';
import LastPageIcon from '@material-ui/icons/LastPage';
import Typography from '@material-ui/core/Typography';
import Grid from '@material-ui/core/Grid';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import CardMedia from '@material-ui/core/CardMedia';
import Hidden from '@material-ui/core/Hidden';
import {NavLink} from "react-router-dom";
import TableCell from "@material-ui/core/TableCell/TableCell";

const styles = theme => ({
    footer: {
        marginTop: '20px',
        textAlign: 'center',
        backgroundColor: '#3f51b5',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#fff'
    },
    home__card: {
        display: 'flex',
        margin: '2em',
        width: '80vw',
    },
    cardDetails: {
        flex: 1,
    },
    cardMedia: {
        width: 160,
        height: 160
    },
    cardGrid: {
        padding: '2em',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        width: '100%',
    }
});

const actionsStyles = theme => ({
    root: {
        flexShrink: 0,
        color: theme.palette.text.secondary,
    },
});

class TablePaginationActions extends React.Component {
    constructor(props) {
        super(props);
        this.handleFirstPageButtonClick = this.handleFirstPageButtonClick.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
        this.handleNextButtonClick = this.handleNextButtonClick.bind(this);
        this.handleLastPageButtonClick = this.handleLastPageButtonClick.bind(this);
    }

    handleFirstPageButtonClick(event) {
        this.props.onChangePage(event, 0);
    };

    handleBackButtonClick(event) {
        this.props.onChangePage(event, this.props.page - 1);
    };

    handleNextButtonClick(event) {
        this.props.onChangePage(event, this.props.page + 1);
    };

    handleLastPageButtonClick(event) {
        this.props.onChangePage(
            event,
            Math.max(0, Math.ceil(this.props.count / this.props.rowsPerPage) - 1),
        );
    };

    render() {
        const {classes, count, page, rowsPerPage, theme} = this.props;

        return (
            <div className={classes.root}>
                <IconButton
                    onClick={this.handleFirstPageButtonClick}
                    disabled={page === 0}
                    aria-label="First Page"
                >
                    <FirstPageIcon/>
                </IconButton>
                <IconButton
                    onClick={this.handleBackButtonClick}
                    disabled={page === 0}
                    aria-label="Previous Page"
                >
                    <KeyboardArrowLeft/>
                </IconButton>
                <IconButton
                    onClick={this.handleNextButtonClick}
                    disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                    aria-label="Next Page"
                >
                    <KeyboardArrowRight/>
                </IconButton>
                <IconButton
                    onClick={this.handleLastPageButtonClick}
                    disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                    aria-label="Last Page"
                >
                    <LastPageIcon/>
                </IconButton>
            </div>
        );
    }
}

const TablePaginationActionsWrapped = withStyles(actionsStyles, {withTheme: true})(
    TablePaginationActions,
);


class Home extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            posts: {},
            loading: true,
            perPage: 10,
            page: 1,
            query: '*'
        };
        console.log(this.context);
    }

    handleChangePage = () => {

    };

    handleChangeRowsPerPage = () => {

    };

    componentDidMount = () => {
        axios.get('/api/v1/posts').then((response) => {
            this.setState({
                posts: response.data,
                loading: false,
            })
        })
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                {this.state.loading === true &&
                <React.Fragment>
                    <Paper>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper>
                </React.Fragment>
                }
                {this.state.loading === false &&
                <React.Fragment>
                    <Grid container className={classes.cardGrid}>
                        {this.state.posts['data'].map(post => {
                            return (
                                <Grid item key={post.title} xs={12} md={12}>
                                    <Card className={classes.home__card}>
                                        <div className={classes.cardDetails}>
                                            <CardContent>
                                                <Typography component="h2" variant="h5">
                                                    {post.title}
                                                </Typography>
                                                <Typography variant="subtitle1" color="textSecondary">
                                                    {post.shortDescription}
                                                </Typography>
                                                <Typography variant="subtitle1" color="primary">
                                                    <NavLink to={"/post/" + post.id}>
                                                        Czytaj dalej
                                                    </NavLink>
                                                </Typography>
                                            </CardContent>
                                        </div>
                                        <Hidden xsDown>
                                            <img src={post.thumbnail} className={classes.cardMedia}/>
                                        </Hidden>
                                    </Card>
                                </Grid>
                            )
                        })}

                        <Paper>
                            <TablePagination
                                colSpan={10}
                                count={this.state.posts.total}
                                rowsPerPage={this.state.perPage}
                                page={this.state.posts.page - 1}
                                onChangePage={this.handleChangePage}
                                onChangeRowsPerPage={this.handleChangeRowsPerPage}
                                ActionsComponent={TablePaginationActionsWrapped}
                            />
                        </Paper>
                    </Grid>
                </React.Fragment>
                }
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(Home);