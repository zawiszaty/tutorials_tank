import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import {withSnackbar} from 'notistack';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import TableFooter from '@material-ui/core/TableFooter';
import TablePagination from '@material-ui/core/TablePagination';
import CircularProgress from "@material-ui/core/CircularProgress/CircularProgress";
import IconButton from "@material-ui/core/IconButton";
import KeyboardArrowRight from '@material-ui/icons/KeyboardArrowRight';
import KeyboardArrowLeft from '@material-ui/icons/KeyboardArrowLeft';
import FirstPageIcon from '@material-ui/icons/FirstPage';
import LastPageIcon from '@material-ui/icons/LastPage';
import CategoryPanelForm from './../Category/Panel/CategoryPanelForm';
import Checkbox from '@material-ui/core/Checkbox';

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '80%',
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

const actionsStyles = theme => ({
    root: {
        flexShrink: 0,
        color: theme.palette.text.secondary,
        marginLeft: theme.spacing.unit * 2.5,
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

class SelectCategory extends React.Component {
    constructor(props) {
        super(props);
        const {onPresentSnackbar} = this.props;

        this.state = {
            onPresentSnackbar: onPresentSnackbar,
            categories: [],
            loaded: true,
            perPage: 10,
            page: 1,
            query: '*'
        }
        ;

    }

    componentDidMount = () => {
        this.getAllCategory()
    }

    getAllCategory = () => {
        this.setState({
            loaded: true,
        });
        axios.get(`/api/category?page=${this.state.page}&limit=${this.state.perPage}&query=${this.state.query}`).then((response) => {
            this.setState({
                categories: response.data,
                loaded: false,
            })
            console.log(response.data)
        });
    }

    handleChangePage = (e, page) => {
        this.setState({
            page: page + 1
        }, () => {
            this.getAllCategory()
        })
    }

    handleChangeRowsPerPage = (e) => {
        this.setState({
            perPage: e.target.value
        }, () => {
            this.getAllCategory();
        });
    }

    handleChangeQuery = (e) => {
        this.setState({
            query: e
        }, () => {
            this.getAllCategory();
        });
    };

    render() {
        const classes = this.props.classes;
        let categoryView;
        if (this.state.loaded === true) {
            categoryView = <CircularProgress className={classes.progress} color="secondary"/>
        } else {
            categoryView = <Table className={classes.table}>
                <TableHead>
                    <TableRow>
                        <TableCell>Wybierz Kategorie</TableCell>
                        <TableCell>Id</TableCell>
                        <TableCell>Nazwa</TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {this.state.categories['data'].map(category => {
                        return (
                            <TableRow key={category.id}>
                                <TableCell padding="checkbox">
                                    <Checkbox
                                        indeterminate={this.props.category > 0}
                                        checked={this.props.category === category.id}
                                        onChange={() => {
                                            this.props.handleChangeCategory(category.id)
                                        }}
                                    />
                                </TableCell>
                                <TableCell component="th" scope="row">
                                    {category.id}
                                </TableCell>
                                <TableCell scope="row">
                                    {category.name}
                                </TableCell>
                            </TableRow>
                        );
                    })}
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TablePagination
                            colSpan={10}
                            count={this.state.categories.total}
                            rowsPerPage={this.state.perPage}
                            page={this.state.categories.page - 1}
                            onChangePage={this.handleChangePage}
                            onChangeRowsPerPage={this.handleChangeRowsPerPage}
                            ActionsComponent={TablePaginationActionsWrapped}
                        />
                    </TableRow>
                </TableFooter>
            </Table>
        }
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <CategoryPanelForm handleChange={this.handleChangeQuery} query={this.state.query}/>
                        {categoryView}
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(SelectCategory));